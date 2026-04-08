#!/bin/bash
# ШиноСервис — деплой в Railway через GitHub
# Использование: ./deploy.sh "описание изменений"

set -e

MSG="${1:-update}"
BRANCH="main"

echo "🚀 ШиноСервис deploy: $MSG"
echo "─────────────────────────────"

# Инициализация git (первый раз)
if [ ! -d ".git" ]; then
  echo "📁 Инициализирую git репозиторий..."
  git init
  git branch -M $BRANCH

  echo ""
  echo "⚠️  Укажи URL репозитория на GitHub:"
  echo "   Ьример: https://github.com/ИМЯ/shinoserv.git"
  read -p "   GitHub URL: " REPO_URL
  git remote add origin "$REPO_URL"
fi

# Добавляем все изменения
git add .

# Проверяем есть ли что коммитить
if git diff --cached --quiet; then
  echo "✅ Нет изменений для деплоя."
  exit 0
fi

# Коммит
git commit -m "$MSG"

# Пуш (Railway автоматически задеплоит после push)
echo "📤 Отправляю в GitHub..."
git push -u origin $BRANCH

echo ""
echo "✅ Готово! Railway начнёт деплой автоматически."
echo "   Следи за прогрессом на: https://railway.app"
