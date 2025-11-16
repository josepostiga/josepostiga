#!/bin/bash

# Script to create symbolic links from .bin folder to /usr/local/bin
# Usage: ./link-bin-folder.sh

set -e

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo "Please run as root (sudo)"
    exit 1
fi

# Path to source and target directories
BIN_SOURCE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.bin" && pwd)"
BIN_TARGET_DIR="/usr/local/bin"

# Create source directory if it doesn't exist
mkdir -p "$BIN_SOURCE_DIR"

# Create symbolic links for all files in .bin
for file in "$BIN_SOURCE_DIR"/*; do
    if [ -f "$file" ] && [ -x "$file" ]; then
        ln -sf "$file" "$BIN_TARGET_DIR/$(basename "$file")"
        echo "Linked: $(basename "$file")"
    fi
done

echo "Finished linking executables to $BIN_TARGET_DIR"
