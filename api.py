from flask import Flask, request, jsonify
import pickle
import pandas as pd
import re
from sklearn.neighbors import NearestNeighbors

app = Flask(__name__)

# Load model dan data
with open('tfidf_vectorizer.pkl', 'rb') as f:
    tfidf_vectorizer = pickle.load(f)

with open('kmeans_model.pkl', 'rb') as f:
    kmeans_model = pickle.load(f)

df_clustered = pd.read_pickle('df_clustered.pkl')

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

def ingredient_match_score(user_cleaned, recipe_cleaned):
    user_items = user_cleaned.split(", ")
    recipe_items = recipe_cleaned.split(", ")

    match = 0
    for u in user_items:
        for r in recipe_items:
            if u in r or r in u:
                match += 1
                break

    return match / len(user_items) if user_items else 0

@app.route('/recommend', methods=['POST'])
def recommend():
    data = request.get_json()
    user_input = data.get('ingredients', '')

    if not user_input:
        return jsonify({'error': 'Ingredients required'}), 400

    user_cleaned = preprocess_user_input(user_input)
    user_vector = tfidf_vectorizer.transform([user_cleaned])

    # Cluster
    user_cluster = kmeans_model.predict(user_vector)[0]
    candidate_idx = df_clustered[df_clustered["cluster"] == user_cluster].index
    X_candidates = tfidf_vectorizer.transform(df_clustered.loc[candidate_idx, "Ingredients Cleaned"])

    knn = NearestNeighbors(n_neighbors=len(candidate_idx), metric="cosine")
    knn.fit(X_candidates)

    distances, indices = knn.kneighbors(user_vector)

    results = []
    for dist, idx in zip(distances[0][:5], indices[0][:5]):  # Top 5
        real_idx = candidate_idx[idx]
        recipe = df_clustered.loc[real_idx]

        coverage = ingredient_match_score(user_cleaned, recipe["Ingredients Cleaned"])

        if coverage < 0.5:
            continue

        similarity = 1 - dist
        final_score = 0.5 * similarity + 0.5 * coverage

        results.append({
            'title': recipe['Title'],
            'ingredients': recipe['Ingredients Cleaned'],
            'steps': recipe['Steps'],
            'similarity': round(similarity, 3),
            'ingredient_match': round(coverage, 3),
            'final_score': round(final_score, 3)
        })

    results = sorted(results, key=lambda x: x['final_score'], reverse=True)

    return jsonify(results)

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)