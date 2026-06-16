#!/bin/bash
# gmw-easy-manage — sign update.json using Ed25519 private key
# Usage: bash sign-update.sh <version>
# Note: When building the zip, use --prefix so the zip has a top-level
# directory matching the plugin slug. Otherwise WordPress uses the temp
# filename (which has a random suffix) as the plugin directory on update.
#   git archive --format=zip --prefix=gmw-easy-manage/ HEAD -o /tmp/gmw-easy-manage.zip
set -e

VERSION="${1:-}"
if [ -z "$VERSION" ]; then
    echo "Usage: $0 <version>"
    exit 1
fi

KEY_FILE="$(dirname "$0")/ed25519-secret.key"
if [ ! -f "$KEY_FILE" ]; then
    echo "Error: $KEY_FILE not found"
    exit 1
fi

DOWNLOAD_URL="https://apps.gmwsys.com/gmw-easy-manage-update/gmw-easy-manage.zip"

SIGNATURE=$(php -r '
$sk = sodium_hex2bin(trim(file_get_contents("'"$KEY_FILE"'")));
$payload = json_encode(["version" => "'"$VERSION"'", "download_url" => "'"$DOWNLOAD_URL"'"], JSON_UNESCAPED_SLASHES);
echo sodium_bin2hex(sodium_crypto_sign_detached($payload, $sk));
')

cat > /tmp/update.json <<EOF
{
  "version": "$VERSION",
  "download_url": "$DOWNLOAD_URL",
  "tested": "6.7",
  "requires": "6.0",
  "homepage": "https://github.com/gmwnet/gmw-easy-manage",
  "signature": "$SIGNATURE"
}
EOF

echo "Signed update.json for v$VERSION"
echo "Signature: $SIGNATURE"
