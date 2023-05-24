rm -rf ./boilerplate
gh release download --pattern 'boilerplate*.zip' --repo 'statik-space/statik-boilerplate' --clobber --output boilerplate.zip
unzip -d ./boilerplate -q boilerplate.zip
rm -rf ./boilerplate/backend/mu-plugins/plugins/statik-blocks
rm -rf ./boilerplate.zip
