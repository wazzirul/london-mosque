# /bin/sh
# Created by Reksa Rangga Wardhana
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
HOSTNAME="45.77.247.110"
USERNAME="root"
ZIP_FILE="build.zip"
TEMP_DIRECTORY=/tmp/
DEPLOY_DIRECTORY=/var/www/london-mosques-api/
DEPLOY_TEMP_DIRECTORY=/tmp/london-mosques-api/
KEY_PAIR=/c/Users/Faisal/.ssh/id_rsa
CONFIG_FILE=/var/local/environments/london-mosques-api/.env

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
cd $TEMP_DIRECTORY && sudo unzip $ZIP_FILE -d $DEPLOY_TEMP_DIRECTORY && sudo mkdir -p $DEPLOY_DIRECTORY
sudo cp -R $DEPLOY_TEMP_DIRECTORY* $DEPLOY_DIRECTORY
sudo chown -R nginx:nginx $DEPLOY_DIRECTORY/storage/
sudo chown -R nginx:nginx $DEPLOY_DIRECTORY/public/
yes | sudo cp $CONFIG_FILE $DEPLOY_DIRECTORY
cd $DEPLOY_DIRECTORY && sudo $(which php) artisan migrate -q
cd $TEMP_DIRECTORY && sudo rm -rf $ZIP_FILE $DEPLOY_TEMP_DIRECTORY"

rm -f $ZIP_FILE

echo "Deploy succesfully."

