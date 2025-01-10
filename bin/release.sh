#!/usr/bin/env bash

set -e
if (( "$#" == 0 ))
then
    echo "Tag has to be provided"

    exit 1
fi

NOW=$(date +%s)
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
VERSION=$1
BASEPATH=$(cd `dirname $0`; cd ../src/; pwd)

# Always prepend with "v"
#if [[ $VERSION != v*  ]]
#then
#    VERSION="v$VERSION"
#fi

if [ -z $2 ] ; then
    repos=$(ls $BASEPATH)
else
    repos=${@:2}
fi

for REMOTE in $repos
do
    echo ""
    echo ""
    echo "Cloning $REMOTE";
    TMP_DIR="/tmp/mineAdmin-split"
    REMOTE_URL="git@github.com:mineadmin/$REMOTE.git"

    rm -rf $TMP_DIR;
    mkdir $TMP_DIR;

    (
        cd $TMP_DIR;

        git clone $REMOTE_URL .
        git checkout "$CURRENT_BRANCH";
        # 判断 tag 是否存在
        if git rev-parse $VERSION >/dev/null 2>&1
        then
            echo "Tag $VERSION already exists"
        else
            echo "Tag $VERSION not exists"
            echo "Releasing $REMOTE"
            git tag $VERSION
            git push origin --tags
        fi
    )
done

TIME=$(echo "$(date +%s) - $NOW" | bc)

printf "Execution time: %f seconds" $TIME