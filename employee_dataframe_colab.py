# Simple Pandas DataFrame for Employee Database in Google Colab

import pandas as pd

# Create a dictionary with employee data
data = {
    'Sr No': [1, 2, 3],
    'Name': ['Alice', 'Bob', 'Charlie'],
    'Mobile No': ['1234567890', '0987654321', '1122334455'],
    'City': ['New York', 'Los Angeles', 'Chicago']
}

# Create DataFrame
df = pd.DataFrame(data)

# Display the DataFrame
print(df)
