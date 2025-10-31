<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Cookin' - Recipe Helper</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!-- Tailwind and app css -->
  @vite('resources/css/app.css')

  <style>
    /* Small UI niceties */
    body { background: linear-gradient(180deg,#fbfbfc 0%, #ffffff 100%); }
  </style>
</head>
<body class="min-h-screen text-slate-700 antialiased">

  <div class="max-w-6xl mx-auto px-6 py-12">
    <header class="flex items-center justify-between mb-8">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-coral-100 rounded-2xl flex items-center justify-center shadow-sm">
          <!-- simple logo mark -->
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" class="text-coral-500">
            <path d="M12 3l7 7-7 7-7-7 7-7z" fill="currentColor" />
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-semibold">Cookin'</h1>
          <p class="text-sm text-slate-500">Pick ingredients and generate a delicious recipe instantly</p>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <button id="helpBtn" class="px-4 py-2 text-sm rounded-full border border-slate-200 bg-white shadow-sm hover:shadow">
          How it works
        </button>
        <a href="#" class="hidden md:inline-flex items-center gap-2 px-4 py-2 rounded-full bg-coral-500 text-white text-sm shadow hover:opacity-95">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M12 2v20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          Save
        </a>
      </div>
    </header>

    <main class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Left: Ingredient Picker -->
      <section class="lg:col-span-1 bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-medium">Ingredients</h2>
          <div class="text-sm text-slate-400" id="selectedCount">0 selected</div>
        </div>

        <div class="space-y-3">
          <div class="relative">
            <input id="search" type="search" placeholder="Search ingredients" class="w-full border border-slate-100 rounded-lg px-4 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-coral-100" />
            <button id="clearSearch" class="absolute right-2 top-2 text-slate-400 hidden">✕</button>
          </div>

          <div class="flex gap-2 flex-wrap" id="selectedChips" aria-live="polite"></div>

          <div class="flex items-center justify-between text-sm text-slate-500 mt-2 mb-1">
            <div>
              <button id="selectAll" class="underline">Select all</button>
              <span class="px-1">•</span>
              <button id="clearAll" class="underline">Clear</button>
            </div>
            <div>
              <label class="inline-flex items-center gap-2">
                <input id="onlyCommon" type="checkbox" class="rounded border-slate-200" />
                <span>Only common</span>
              </label>
            </div>
          </div>

          <div id="ingredientsList" class="max-h-64 overflow-auto divide-y divide-slate-100 rounded-lg border border-slate-50 p-2">
            <!-- checkboxes injected via JS -->
          </div>
        </div>
      </section>

      <!-- Middle: Controls & Generate -->
      <section class="lg:col-span-1 bg-white rounded-xl shadow p-6 flex flex-col">
        <h3 class="text-lg font-medium mb-4">Recipe Options</h3>

        <div class="space-y-4 flex-1">
          <div>
            <label class="block text-sm text-slate-500 mb-1">Cuisine</label>
            <select id="cuisine" class="w-full rounded-lg border border-slate-100 px-4 py-2 focus:ring-coral-100">
              <option>Any</option>
              <option>Italian</option>
              <option>Mediterranean</option>
              <option>Mexican</option>
              <option>Asian</option>
              <option>American</option>
            </select>
          </div>

          <div>
            <label class="block text-sm text-slate-500 mb-1">Dietary preference</label>
            <select id="diet" class="w-full rounded-lg border border-slate-100 px-4 py-2 focus:ring-coral-100">
              <option>None</option>
              <option>Vegetarian</option>
              <option>Vegan</option>
              <option>Gluten-free</option>
              <option>Low-carb</option>
            </select>
          </div>

          <div>
            <label class="block text-sm text-slate-500 mb-1">Serving size</label>
            <input id="servings" type="number" min="1" value="2" class="w-32 rounded-lg border border-slate-100 px-4 py-2" />
          </div>
        </div>

        <div class="mt-6">
          <button id="generateBtn" class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-xl bg-coral-500 text-white font-semibold hover:opacity-95 shadow">
            <svg id="genIcon" width="18" height="18" viewBox="0 0 24 24" fill="none">
              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Generate Recipe
          </button>

          <p class="text-xs text-slate-400 mt-3">
            This will send the selected ingredients & options to your recipe generator (replace <code>/api/generate</code> in the script with your API).
          </p>
        </div>
      </section>

      <!-- Right: Result -->
      <section class="lg:col-span-1 bg-white rounded-xl shadow p-6 flex flex-col">
        <div class="flex items-start justify-between">
          <h3 class="text-lg font-medium">Recipe Preview</h3>
          <div class="text-sm text-slate-400">Instant</div>
        </div>

        <div id="resultArea" class="mt-4 flex-1 flex flex-col gap-4">
          <div id="placeholder" class="rounded-xl border border-slate-50 p-4 text-slate-500">
            Select ingredients and click <strong>Generate Recipe</strong>.
          </div>

          <!-- generated recipe card -->
          <article id="recipeCard" class="hidden rounded-xl border border-slate-50 p-4 bg-gradient-to-b from-white to-slate-50">
            <header class="mb-3">
              <h4 id="recipeTitle" class="text-xl font-semibold">Title</h4>
              <div class="text-sm text-slate-400" id="metaInfo">Serves 2 • 25 min • Italian</div>
            </header>

            <section class="mb-3">
              <h5 class="font-medium text-sm mb-2">Ingredients</h5>
              <ul id="recipeIngredients" class="list-inside list-disc text-sm text-slate-700"></ul>
            </section>

            <section>
              <h5 class="font-medium text-sm mb-2">Instructions</h5>
              <ol id="recipeSteps" class="list-decimal list-inside text-sm text-slate-700 space-y-2"></ol>
            </section>
          </article>
        </div>

        <div class="mt-4 flex gap-2">
          <button id="copyBtn" class="flex-1 py-2 rounded-lg border border-slate-100 text-sm hover:shadow">Copy recipe</button>
          <button id="downloadBtn" class="flex-1 py-2 rounded-lg bg-slate-700 text-white text-sm hover:opacity-95">Download</button>
        </div>
      </section>
    </main>

    <footer class="mt-10 text-sm text-slate-400 text-center">
      Built with ❤️ — replace <code>/api/generate</code> with your ChatGPT backend to produce AI recipes.
    </footer>
  </div>

  <script>
    // ===== sample ingredients dataset =====
    const INGREDIENTS = [
      "Chicken breast", "Ground beef", "Salmon", "Shrimp", "Tofu",
      "Eggs", "Milk", "Butter", "Cream", "Cheddar cheese",
      "Parmesan", "Mozzarella", "Tomato", "Onion", "Garlic",
      "Bell pepper", "Mushrooms", "Spinach", "Kale", "Potato",
      "Sweet potato", "Rice", "Pasta", "Quinoa", "Black beans",
      "Chickpeas", "Lentils", "Olive oil", "Soy sauce", "Lemon",
      "Basil", "Parsley", "Rosemary", "Thyme", "Chili flakes",
      "Cumin", "Paprika", "Cinnamon", "Honey", "Bread"
    ];

    // ===== DOM refs =====
    const ingredientsList = document.getElementById('ingredientsList');
    const search = document.getElementById('search');
    const clearSearch = document.getElementById('clearSearch');
    const selectedChips = document.getElementById('selectedChips');
    const selectedCount = document.getElementById('selectedCount');
    const selectAllBtn = document.getElementById('selectAll');
    const clearAllBtn = document.getElementById('clearAll');
    const onlyCommon = document.getElementById('onlyCommon');
    const generateBtn = document.getElementById('generateBtn');
    const resultArea = document.getElementById('resultArea');
    const placeholder = document.getElementById('placeholder');
    const recipeCard = document.getElementById('recipeCard');
    const recipeTitle = document.getElementById('recipeTitle');
    const recipeIngredients = document.getElementById('recipeIngredients');
    const recipeSteps = document.getElementById('recipeSteps');
    const metaInfo = document.getElementById('metaInfo');
    const copyBtn = document.getElementById('copyBtn');
    const downloadBtn = document.getElementById('downloadBtn');

    // maintain selected set
    const selected = new Set();

    // ===== render ingredient list =====
    function renderList(filter = '') {
      const frag = document.createDocumentFragment();
      const q = filter.trim().toLowerCase();
      ingredientsList.innerHTML = '';

      const display = INGREDIENTS
        .filter(i => !onlyCommon.checked || isCommon(i))
        .filter(i => i.toLowerCase().includes(q));

      if (display.length === 0) {
        ingredientsList.innerHTML = '<div class="p-4 text-sm text-slate-400">No ingredients found</div>';
        return;
      }

      for (const item of display) {
        const id = 'ing-' + item.replace(/\s+/g, '-').toLowerCase();
        const row = document.createElement('label');
        row.className = 'flex items-center gap-3 px-3 py-2 hover:bg-slate-50 rounded-md cursor-pointer';
        row.innerHTML = `
          <input id="${id}" type="checkbox" class="h-4 w-4 rounded border-slate-200" ${selected.has(item) ? 'checked' : ''}/>
          <div class="flex-1">
            <div class="text-sm font-medium text-slate-700">${item}</div>
            <div class="text-xs text-slate-400">${getHint(item)}</div>
          </div>
        `;
        // checkbox change handler
        row.querySelector('input').addEventListener('change', (e) => {
          if (e.target.checked) selected.add(item);
          else selected.delete(item);
          updateSelectedUI();
        });

        frag.appendChild(row);
      }

      ingredientsList.appendChild(frag);
    }

    // small helper: pretend some ingredients are 'common'
    function isCommon(name) {
      const commons = ["Tomato","Onion","Garlic","Olive oil","Salt","Eggs","Milk","Butter","Rice","Pasta","Chicken breast","Cheddar cheese","Bread"];
      return commons.includes(name);
    }

    function getHint(name){
      if (isCommon(name)) return 'Common pantry item';
      return 'Great choice!';
    }

    // ===== selected chips UI =====
    function updateSelectedUI(){
      selectedChips.innerHTML = '';
      if (selected.size === 0) {
        selectedCount.textContent = '0 selected';
      } else {
        selectedCount.textContent = `${selected.size} selected`;
        for (const s of selected) {
          const chip = document.createElement('div');
          chip.className = 'flex items-center gap-2 bg-slate-50 px-3 py-1 rounded-lg text-sm';
          chip.innerHTML = `<span>${s}</span><button data-name="${s}" class="text-slate-400 hover:text-slate-600">✕</button>`;
          chip.querySelector('button').addEventListener('click', (e) => {
            selected.delete(e.currentTarget.dataset.name);
            // uncheck the checkbox if present
            const id = 'ing-' + e.currentTarget.dataset.name.replace(/\s+/g, '-').toLowerCase();
            const cb = document.getElementById(id);
            if (cb) cb.checked = false;
            updateSelectedUI();
            renderList(search.value);
          });
          selectedChips.appendChild(chip);
        }
      }
    }

    // ===== search input handlers =====
    search.addEventListener('input', (e) => {
      const q = e.target.value;
      renderList(q);
      clearSearch.classList.toggle('hidden', q.trim() === '');
    });
    clearSearch.addEventListener('click', () => {
      search.value = '';
      renderList('');
      clearSearch.classList.add('hidden');
    });

    // ===== select all / clear all =====
    selectAllBtn.addEventListener('click', (e) => {
      for (const i of INGREDIENTS) selected.add(i);
      updateSelectedUI();
      renderList(search.value);
    });
    clearAllBtn.addEventListener('click', (e) => {
      selected.clear();
      updateSelectedUI();
      renderList(search.value);
    });

    onlyCommon.addEventListener('change', () => renderList(search.value));

    // ===== generation logic =====
    async function generateRecipe() {
      if (selected.size === 0) {
        alert('Please select at least one ingredient.');
        return;
      }

      // UI: show spinner state
      generateBtn.disabled = true;
      const originalLabel = generateBtn.innerHTML;
      generateBtn.innerHTML = `<svg class="animate-spin" width="18" height="18" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="white" stroke-width="3" stroke-linecap="round" fill="none"></circle></svg> Generating...`;

      const payload = {
        ingredients: Array.from(selected),
        cuisine: document.getElementById('cuisine').value,
        diet: document.getElementById('diet').value,
        servings: Number(document.getElementById('servings').value || 2)
      };

      try {
        // Replace this URL with your ChatGPT-backed endpoint
        const resp = await fetch('/api/generate', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload),
        });

        if (!resp.ok) {
          // If there's no backend yet, fallback to the local generator
          throw new Error('No backend or network error, using fallback generator.');
        }

        // expecting a JSON with structure { title, ingredients: [], steps: [], timeMinutes, servings, cuisine }
        const json = await resp.json();
        showRecipe(json);
      } catch (err) {
        console.warn(err);
        // fallback local generation for demo/testing
        const demo = localGenerate(payload);
        showRecipe(demo);
      } finally {
        generateBtn.disabled = false;
        generateBtn.innerHTML = originalLabel;
      }
    }

    // ===== show recipe in UI =====
    function showRecipe(data) {
      placeholder.classList.add('hidden');
      recipeCard.classList.remove('hidden');

      recipeTitle.textContent = data.title || 'Delicious Dish';
      recipeIngredients.innerHTML = '';
      (data.ingredients || []).forEach(i => {
        const li = document.createElement('li');
        li.textContent = i;
        recipeIngredients.appendChild(li);
      });

      recipeSteps.innerHTML = '';
      (data.steps || []).forEach(s => {
        const li = document.createElement('li');
        li.textContent = s;
        recipeSteps.appendChild(li);
      });

      const time = data.timeMinutes ? `${data.timeMinutes} min` : '—';
      const cuisine = data.cuisine || 'Any';
      const servings = data.servings || '2';
      metaInfo.textContent = `Serves ${servings} • ${time} • ${cuisine}`;
    }

    // ===== basic fallback local generator (non-AI), purely illustrative =====
    function localGenerate({ ingredients, cuisine, diet, servings }) {
      const goodTitle = `${cuisine === 'Any' ? '' : cuisine + ' ' }${ingredients[0]} & ${ingredients[1] || 'veggies'} Bowl`.trim();
      const ingrList = ingredients.map(i => `${i} — 1 portion`).slice(0, 12);
      const steps = [
        `Prep and chop the ingredients: ${ingredients.slice(0,4).join(', ')}.`,
        `Heat a pan with olive oil. Sauté onions and garlic until translucent.`,
        `Add ${ingredients[0]} and cook until done. Add remaining ingredients and season to taste.`,
        `Simmer for 8–12 minutes. Adjust seasoning and serve with a squeeze of lemon or grated cheese.`
      ];
      return {
        title: goodTitle,
        ingredients: ingrList,
        steps,
        timeMinutes: 25,
        servings,
        cuisine
      };
    }

    // ===== copy & download functionality =====
    copyBtn.addEventListener('click', () => {
      if (recipeCard.classList.contains('hidden')) return;
      const text = [
        recipeTitle.textContent,
        metaInfo.textContent,
        '\nIngredients:\n' + [...recipeIngredients.querySelectorAll('li')].map(li => '- ' + li.textContent).join('\n'),
        '\nInstructions:\n' + [...recipeSteps.querySelectorAll('li')].map((li,i) => `${i+1}. ${li.textContent}`).join('\n')
      ].join('\n\n');
      navigator.clipboard.writeText(text).then(() => {
        copyBtn.textContent = 'Copied!';
        setTimeout(()=> copyBtn.textContent = 'Copy recipe', 1500);
      });
    });

    downloadBtn.addEventListener('click', () => {
      if (recipeCard.classList.contains('hidden')) return;
      const filename = (recipeTitle.textContent || 'recipe').toLowerCase().replace(/\s+/g,'-') + '.txt';
      const text = [
        recipeTitle.textContent,
        metaInfo.textContent,
        '\nIngredients:\n' + [...recipeIngredients.querySelectorAll('li')].map(li => '- ' + li.textContent).join('\n'),
        '\nInstructions:\n' + [...recipeSteps.querySelectorAll('li')].map((li,i) => `${i+1}. ${li.textContent}`).join('\n')
      ].join('\n\n');
      const blob = new Blob([text], { type: 'text/plain' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url; a.download = filename; document.body.appendChild(a); a.click();
      a.remove(); URL.revokeObjectURL(url);
    });

    // ===== initialize =====
    document.addEventListener('DOMContentLoaded', () => {
      renderList();
      updateSelectedUI();

      generateBtn.addEventListener('click', generateRecipe);

      // optional help dialog
      document.getElementById('helpBtn').addEventListener('click', () => {
        alert('Select ingredients then click Generate. Replace /api/generate with your ChatGPT backend to generate AI recipes.');
      });
    });

  </script>
</body>
</html>
