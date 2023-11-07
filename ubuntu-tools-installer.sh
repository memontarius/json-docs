#!/bin/bash

NVM=1
COMPOSER=1
DOCKER=1

for argument in "$@"
do
  case $argument in
    "no-nvm") NVM=0 ;;
    "no-composer") COMPOSER=0 ;;
    "no-docker") DOCKER=0 ;;
  esac
done

sudo apt-get update
sudo apt-get install make

if [ $NVM -eq 1 ]; then
    curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.5/install.sh | bash
    . ~/.nvm/nvm.sh
    . ~/.profile
    . ~/.bashrc
    nvm install 18
    source ~/.bashrc

    printf "\n------------------\n NVM finished \n------------------\n"
fi

if [ $DOCKER -eq 1 ]; then
    sudo apt-get update
    sudo apt-get install -y ca-certificates curl gnupg
    sudo install -y -m 0755 -d /etc/apt/keyrings
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
    sudo chmod a+r /etc/apt/keyrings/docker.gpg

    echo \
      "deb [arch="$(dpkg --print-architecture)" signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
      "$(. /etc/os-release && echo "$VERSION_CODENAME")" stable" | \
      sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

    sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
    sudo usermod -aG docker "$USER";

    printf "\n------------------\n DOCKER finished \n------------------\n"
fi

if [ $COMPOSER -eq 1 ]; then
    sudo apt-get update
    sudo apt-get install -y php-xml
    sudo apt install -y curl php-cli php-mbstring git unzip php-curl
    curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
    REMOTE_HASH=`curl -sS https://composer.github.io/installer.sig`
    LOCAL_HASH=`php -r "echo hash_file('SHA384', '/tmp/composer-setup.php');"`

    if [ "$REMOTE_HASH" != "$LOCAL_HASH" ]; then
        echo "Installer corrupt"
        exit
    fi

    sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer

    printf "\n------------------\n COMPOSER finished \n------------------\n"
fi
