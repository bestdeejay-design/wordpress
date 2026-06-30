# Развёртывание — ti200.ru (WordPress)

## Репозиторий
```
https://github.com/bestdeejay-design/wordpress
```

## Ветки
- `main` — продакшен. Любой пуш запускает деплой на сервер.

## Процесс деплоя

```
Локально → git add → git commit → git push
                                        ↓
                              GitHub Actions
                            (bestdeejay-design/wordpress)
                                        ↓
                        1. rsync на сервер (85.143.101.97)
                        2. docker compose up -d
```

## Как внести изменения

1. Изменить файлы локально
2. `git add -A && git commit -m "тип: описание"`
3. `git push`
4. GitHub Actions сам задеплоит на сервер

## Как откатить

```bash
git revert <commit-hash> --no-edit
git push
```

GitHub Actions сам задеплоит отменённую версию на сервер.

## Требования к коммитам

- Типы: `feat:`, `fix:`, `chore:`, `docs:`, `test:`
- Писать по-русски, кратко и понятно

## CI/CD

- Workflow: `.github/workflows/deploy.yml`
- Secret: `SSH_PRIVATE_KEY` — ключ для подключения к серверу
