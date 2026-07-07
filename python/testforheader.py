import os
from datetime import datetime
from egm_connector import *

# Always run from this script’s directory
script_dir = os.path.dirname(os.path.abspath(__file__))
os.chdir(script_dir)

# Google Sheets setup
ss = "CEMENT TRUCK CAPACITY SUMMARY"
set_gspread(ss, "FOR LIST - TRAILER", "./env/cred_gspread_GSDCSUPP_ewul_livedatasync.json")

# Fetch data
data = get_sheet_data("FOR LIST - TRUCK", "B1:AF")
data2 = get_sheet_data("FOR LIST - TRAILER", "B1:AF")
data3 = get_sheet_data("FOR LIST - DRIVER", "A1:S")
data4 = get_sheet_data("FOR LOOK UP - SBUO MONITORING", "A1:T")
data5 = get_sheet_data("FOR LOOK UP - Employee Sheet", "A1:Z")


print("=== FOR LIST - DRIVER ===")
print(data3, "\n")

