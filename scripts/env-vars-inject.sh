#!/bin/bash
set -e

ENVIRONMENT=prod
AWS_REGION=us-east-1
DESTINATION_FILE=/usr/share/nginx/html/.env
DESTINATION_USER=nginx

cd $(dirname "$0")

rm -f $DESTINATION_FILE

aws ssm get-parameters-by-path \
    --region $AWS_REGION \
    --path "/${ENVIRONMENT}/" \
    --with-decryption \
    --recursive \
    --query "Parameters[*].{Name:Name,Value:Value}" | \
  jq --arg ENV_PREFIX "/$ENVIRONMENT/APP_" \
    -r '.[] | select(.Name | startswith($ENV_PREFIX)) | (.Name | split("/")[-1] | gsub("="; "_")) + "=" + .Value' > $DESTINATION_FILE

chown $DESTINATION_USER:$DESTINATION_USER $DESTINATION_FILE

chmod 0400 $DESTINATION_FILE
