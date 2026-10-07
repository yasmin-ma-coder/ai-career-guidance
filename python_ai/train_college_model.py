import pandas as pd
import pickle
from sklearn.tree import DecisionTreeClassifier
from sklearn.preprocessing import LabelEncoder

data = pd.read_csv("college_dataset.csv")

encoders = {}

for column in data.columns:
    encoders[column] = LabelEncoder()
    data[column] = encoders[column].fit_transform(data[column])

X = data[["career", "education", "location", "budget"]]
y_course = data["course"]
y_college = data["college"]

course_model = DecisionTreeClassifier(random_state=42)
college_model = DecisionTreeClassifier(random_state=42)

course_model.fit(X, y_course)
college_model.fit(X, y_college)

pickle.dump(course_model, open("course_model.pkl", "wb"))
pickle.dump(college_model, open("college_model.pkl", "wb"))
pickle.dump(encoders, open("encoders.pkl", "wb"))

print("AI course and college model trained successfully.")