import pandas as pd
import re
from sklearn.feature_extraction.text import TfidfVectorizer
from collections import Counter
from difflib import get_close_matches
import pickle
from sklearn.cluster import KMeans
import numpy as np
from sklearn.neighbors import NearestNeighbors
from mlxtend.preprocessing import TransactionEncoder
from mlxtend.frequent_patterns import apriori, association_rules
import warnings
warnings.filterwarnings("ignore")

# Load CSV
file_path = "storage/app/recipes.csv"  # Sesuaikan path
df = pd.read_csv(file_path)

# Fungsi preprocessing (copy dari script asli)
def preprocess_user_input(user_input_text):
    if not isinstance(user_input_text, str):
        return ""
    units = (
        r'gram|g|gr|kg|ml|l|liter|sdm|sdt|sendok makan|sendok teh|bh|'
        r'butir|buah|siung|lembar|batang|papan|bungkus|sachet'
    )
    items_raw = [item.strip() for item in user_input_text.split(',')]
    cleaned_items = []
    for item in items_raw:
        text = item.lower()
        text = re.sub(r'\(.*?\)', '', text)
        text = re.sub(r'^[0-9¼½¾⅓⅔/.\s]+', '', text).strip()
        text = re.sub(rf'^(?:{units})\s*', '', text).strip()
        text = re.sub(r'[^a-z\s]', ' ', text)
        text = re.sub(r'\s+', ' ', text).strip()
        if text and text not in cleaned_items:
            cleaned_items.append(text)
    return ", ".join(cleaned_items)

# Asumsikan kolom sudah sesuai: Title, Ingredients, Steps, URL, Category, Title Cleaned, Total Ingredients, Ingredients Cleaned, Total Steps

# Filter data kosong
df = df[df['Ingredients Cleaned'] != ''].reset_index(drop=True)

# TF-IDF
vectorizer = TfidfVectorizer(min_df=2, max_df=0.9)
X_tfidf = vectorizer.fit_transform(df['Ingredients Cleaned'].fillna("").astype(str))

# K-Means
kmeans = KMeans(n_clusters=5, random_state=42, n_init=10)
df['cluster'] = kmeans.fit_predict(X_tfidf)

# Simpan
with open('tfidf_vectorizer.pkl', 'wb') as f:
    pickle.dump(vectorizer, f)

with open('kmeans_model.pkl', 'wb') as f:
    pickle.dump(kmeans, f)

df.to_pickle('df_clustered.pkl')

print("Model dan data berhasil disimpan!")

# Market Basket Analysis
ingredients_series = df["Ingredients Cleaned"].dropna()
transactions = ingredients_series.apply(
    lambda x: [item.strip() for item in x.split(",") if item.strip()]
).tolist()

ingredient_counts = Counter()
for items in transactions:
    ingredient_counts.update(items)

frequent_ingredients = {
    ing for ing, cnt in ingredient_counts.items() if cnt >= 10
}

transactions = [
    [ing for ing in items if ing in frequent_ingredients]
    for items in transactions
]

transactions = [t for t in transactions if len(t) >= 2]

te = TransactionEncoder()
te_array = te.fit(transactions).transform(transactions)
df_basket = pd.DataFrame(te_array, columns=te.columns_)

frequent_itemsets = apriori(
    df_basket,
    min_support=0.02,
    use_colnames=True,
    max_len=2
)

rules = association_rules(
    frequent_itemsets,
    metric="confidence",
    min_threshold=0.4
)

rules_sorted = rules.sort_values(by="lift", ascending=False)

rules_sorted[
    ["antecedents", "consequents", "support", "confidence", "lift"]
].head(10).to_csv('mba_rules.csv', index=False)

print("MBA rules saved!")