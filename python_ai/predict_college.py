import sys
import json
import pandas as pd

try:
    career = sys.argv[1].strip()
    education = sys.argv[2].strip()
    location = sys.argv[3].strip()
    budget = sys.argv[4].strip()

    data = pd.read_csv("college_dataset.csv")
    data.columns = data.columns.str.strip().str.lower()

    for col in ["career", "education", "location", "budget"]:
        data[col] = data[col].astype(str).str.strip()

    result = data[
        (data["career"].str.lower() == career.lower()) &
        (data["education"].str.lower() == education.lower()) &
        (data["location"].str.lower() == location.lower()) &
        (data["budget"].str.lower() == budget.lower())
    ]

    if result.empty:
        result = data[
            (data["career"].str.lower() == career.lower()) &
            (data["location"].str.lower() == location.lower()) &
            (data["budget"].str.lower() == budget.lower())
        ]

    if result.empty:
        result = data[
            (data["career"].str.lower() == career.lower()) &
            (data["location"].str.lower() == location.lower())
        ]

    if result.empty:
        result = data[data["career"].str.lower() == career.lower()]

    suggestions = result.head(5).to_dict(orient="records")

    print(json.dumps({
        "suggestions": suggestions
    }, ensure_ascii=False))

except Exception as e:
    print(json.dumps({
        "suggestions": [],
        "error": str(e)
    }))