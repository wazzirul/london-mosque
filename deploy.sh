# /bin/sh
# Created by Illiyin Team
# 

die () {
    echo >&2 "$@"
    exit 1
}

confirm () {
    read -p "Apakah ingin melanjutkan? [Y/n] " -n 1 -r
    [[ $REPLY =~ ^[Nn]$ ]] && die
    echo    # (optional) move to a new line
}

# Credential
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
DEPLOY_CONFIG="deploy/${CURRENT_BRANCH}.json"
HOSTNAME=$(cat $DEPLOY_CONFIG | jq '.ssh.hostname' | tr -d '"')
USERNAME=$(cat $DEPLOY_CONFIG | jq '.ssh.username' | tr -d '"')
ZIP_FILE="build.zip"
TEMP_DIRECTORY=$(cat $DEPLOY_CONFIG | jq '.temp_directory' | tr -d '"')
DEPLOY_DIRECTORY=$(cat $DEPLOY_CONFIG | jq '.deploy_directory' | tr -d '"')
DEPLOY_TEMP_DIRECTORY=$(cat $DEPLOY_CONFIG | jq '.deploy_temp_directory' | tr -d '"')
KEY_PAIR=/c/Users/Wazirul/.ssh/id_rsa
CONFIG_FILE=$(cat $DEPLOY_CONFIG | jq '.env_config' | tr -d '"')
USER=$(cat $DEPLOY_CONFIG | jq '.permissions.user' | tr -d '"')
GROUP=$(cat $DEPLOY_CONFIG | jq '.permissions.group' | tr -d '"')
 
echo "Run composer install command..."
$(which composer) install
confirm

echo "Creating zip file for project..."
zip -r $ZIP_FILE app bootstrap config database public resources routes storage tests vendor README.md artisan composer.json composer.lock package.json phpunit.xml server.php webpack.mix.js

if [ ! -f $ZIP_FILE ]; then
    die "[ERROR] $ZIP_FILE not found."
fi

# unzip $ZIP_FILE -d $TEMP_DIRECTORY
echo "Move file to server..."
scp -r -i $KEY_PAIR $ZIP_FILE $USERNAME@$HOSTNAME:$TEMP_DIRECTORY

echo "Deployment process..."
ssh -t -i $KEY_PAIR $USERNAME@$HOSTNAME "
cd $TEMP_DIRECTORY && unzip $ZIP_FILE -d $DEPLOY_TEMP_DIRECTORY && mkdir -p $DEPLOY_DIRECTORY
cp -R $DEPLOY_TEMP_DIRECTORY* $DEPLOY_DIRECTORY
chown -R $USER:$GROUP $DEPLOY_DIRECTORY/storage/
chown -R $USER:$GROUP $DEPLOY_DIRECTORY/public/
yes | cp $CONFIG_FILE $DEPLOY_DIRECTORY
cd $DEPLOY_DIRECTORY && $(which php) artisan migrate -q
cd $TEMP_DIRECTORY && rm -rf $ZIP_FILE $DEPLOY_TEMP_DIRECTORY"

rm -f $ZIP_FILE

echo "Deploy succesfully."
