#!/bin/bash
# Rename TCA files - remove underscores per TYPO3 convention
# TYPO3 converts CamelCase → lowercase (NO underscores!)
# Author: Ernedin Zajko <ezajko@root.ba>

CSV_FILE="packages/spark-academics/Resources/Private/Data/tca_rename_revert.csv"
TCA_DIR="packages/spark-academics/Configuration/TCA"

echo "=== Reverting TCA Names to Lowercase (no underscores) ==="
echo "TYPO3 Convention: AcademicRank → academicrank"
echo ""

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
echo "✅ TCA rename complete!"
