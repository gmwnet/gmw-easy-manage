#!/bin/bash
# gmw-easy-manage — sign update.json using Ed25519 private key
# Usage: bash sign-update.sh <version>
# Note: When building the zip, use --prefix so the zip has a top-level
# directory matching the plugin slug. Otherwise WordPress uses the temp
# filename (which has a random suffix) as the plugin directory on update.
#   git archive --format=zip --prefix=gmw-easy-manage/ HEAD -o /tmp/gmw-easy-manage.zip
# The ZIP SHA-256 digest is included in the signed payload so tampering with
# the ZIP at the download URL fails verification.
set -e

VERSION="${1:-}"
if [ -z "$VERSION" ]; then
    echo "Usage: $0 <version>"
    exit 1
fi
if ! [[ "$VERSION" =~ ^[a-zA-Z0-9._-]+$ ]]; then
    echo "Error: Invalid version string '${VERSION}'"
    exit 1
fi

KEY_FILE="$(dirname "$0")/ed25519-secret.key"
if [ ! -f "$KEY_FILE" ]; then
    echo "Error: $KEY_FILE not found"
    exit 1
fi

DOWNLOAD_URL="https://apps.gmwsys.com/gmw-easy-manage-update/gmw-easy-manage.zip"

# Build the zip first so its digest can be included in the signed payload.
ZIP_FILE="/tmp/gmw-easy-manage.zip"
git archive --format=zip --prefix=gmw-easy-manage/ HEAD -o "$ZIP_FILE"
SHA256=$(sha256sum "$ZIP_FILE" | awk '{print $1}')

SIGNATURE=$(php -r '
$sk = sodium_hex2bin(trim(file_get_contents("'"$KEY_FILE"'")));
$payload = json_encode(["version" => "'"$VERSION"'", "download_url" => "'"$DOWNLOAD_URL"'", "sha256" => "'"$SHA256"'"], JSON_UNESCAPED_SLASHES);
echo sodium_bin2hex(sodium_crypto_sign_detached($payload, $sk));
')

cat > /tmp/update.json <<EOF
{
  "version": "$VERSION",
  "download_url": "$DOWNLOAD_URL",
  "sha256": "$SHA256",
  "tested": "6.7",
  "requires": "6.0",
  "homepage": "https://github.com/gmwnet/gmw-easy-manage",
  "signature": "$SIGNATURE"
}
EOF

echo "Signed update.json for v$VERSION (sha256 $SHA256)"
echo "ZIP: $ZIP_FILE"
echo "Signature: $SIGNATURE"