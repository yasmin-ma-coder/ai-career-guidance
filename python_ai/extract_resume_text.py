import sys
import json
from PyPDF2 import PdfReader

try:
    pdf_path = sys.argv[1]

    reader = PdfReader(pdf_path)

    text = ""
    for page in reader.pages:
        page_text = page.extract_text()
        if page_text:
            text += page_text + " "

    print(json.dumps({
        "text": text
    }))

except Exception as e:
    print(json.dumps({
        "text": "",
        "error": str(e)
    }))