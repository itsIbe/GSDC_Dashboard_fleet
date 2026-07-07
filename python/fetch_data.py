import os
import json
from datetime import datetime
from egm_connector import *

# --- Always run from this script’s directory ---
script_dir = os.path.dirname(os.path.abspath(__file__))
os.chdir(script_dir)

# --- Google Sheets setup ---
ss = "CEMENT TRUCK CAPACITY SUMMARY"
set_gspread(ss, "FOR LIST - TRAILER", "./env/cred_gspread_GSDCSUPP_ewul_livedatasync.json")

# --- Define output file path (Laravel storage) ---
output_file = os.path.join(script_dir, "..", "storage", "app", "fetch_data.json")

# --- Delete old JSON file if exists (force fresh data) ---
if os.path.exists(output_file):
    try:
        os.remove(output_file)
        print("[INFO] Old fetch_data.json deleted.")
    except Exception as e:
        print(f"[WARN] Could not delete old file: {e}")

# --- Fetch new data from Google Sheets ---
while True:
    print("[INFO] Fetching data from Google Sheets...")
    data = get_sheet_data("FOR LIST - TRUCK", "B1:AF")
    data2 = get_sheet_data("FOR LIST - TRAILER", "B1:AF")
    data3 = get_sheet_data("FOR LIST - DRIVER", "A1:S")
    data4 = get_sheet_data("FOR LOOK UP - SBUO MONITORING", "A1:T")
    data5 = get_sheet_data("FOR LOOK UP - Employee Sheet", "A1:Z")
    data6 = get_sheet_data("FOR LIST - JO DETAIL", "A1:M")
    data7 = get_sheet_data("FOR LIST - JR DETAIL", "A1:M")

    # --- Build JSON payload ---
    json_dump = {
        "result": data,
        "trailer_data": data2,
        "driver_data": data3,
        "forlook-up": data4,
        "employee": data5,
        "jo_detail": data6,
        "jr_detail": data7,
        "last_updated": datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    }

    # --- Write new JSON file ---
    try:
        with open(output_file, "w", encoding="utf-8") as f:
            json.dump(json_dump, f, ensure_ascii=False, indent=2)
        print(f"[SUCCESS] New data written to {output_file}")
    except Exception as e:
        print(f"[ERROR] Failed to write JSON: {e}")
    time.sleep(60*5)