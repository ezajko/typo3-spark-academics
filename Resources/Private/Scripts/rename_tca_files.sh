#!/bin/bash
# Rename TCA files based on CSV mapping
# Author: Ernedin Zajko <ezajko@root.ba>

CSV_FILE="packages/spark-academics/Resources/Private/Data/tca_rename_mapping.csv"
TCA_DIR="packages/spark-academics/Configuration/TCA"

echo "=== TCA File Rename Script ==="
echo "Reading from: $CSV_FILE"
echo ""

# Skip header line, read CSV
tail -n +2 "$CSV_FILE" | while IFS=',' read -r old_name new_name; do
    old_path="$TCA_DIR/$old_name"
    new_path="$TCA_DIR/$new_name"
    
    if [ -f "$old_path" ]; then
        echo "Renaming: $old_name → $new_name"
        git mv "$old_path" "$new_path"
    else
        echo "⚠️  File not found: $old_name"
    fi
done

echo ""
echo "✅ Rename complete!"
echo "Next steps:"
echo "  1. git status (verify renames)"
echo "  2. ddev typo3 database:updateschema"
echo "  3. ddev typo3 cache:flush"
