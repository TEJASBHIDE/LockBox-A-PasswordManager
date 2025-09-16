import matplotlib.pyplot as plt
import numpy as np

# Generate sample data
x = np.random.randn(100)
y = np.random.randn(100)

# Scatter plot
plt.figure(figsize=(10,4))

plt.subplot(1, 2, 1)
plt.scatter(x, y, color='blue', alpha=0.5)
plt.title('Scatter Plot')
plt.xlabel('X')
plt.ylabel('Y')

# Histogram
plt.subplot(1, 2, 2)
plt.hist(x, bins=20, color='green', alpha=0.7)
plt.title('Histogram of X')
plt.xlabel('Value')
plt.ylabel('Frequency')

plt.tight_layout()
plt.show()
