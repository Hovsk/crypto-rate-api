# Crypto Rate API (BTC to USD/EUR/GBP)

A REST API that provides hourly exchange rates for Bitcoin against other currencies. Powered by CoinGecko.

---

## Quick Start

```bash
git clone https://github.com/Hovsk/crypto-rate-api.git
cd crypto-rate-api

make setup
```

> Requirements:
> - Docker + Docker Compose
> - Symfony CLI (`brew install symfony-cli` or https://symfony.com/download)

---

## Environment Configuration

The `.env` file is automatically updated via `make env`.

```dotenv
COINGECKO_API_URL=https://api.coingecko.com/api/v3
COINGECKO_ASSET_ID=bitcoin
```

---

## Fetch Exchange Rates

```http
GET /api/rates?pair=BTC/USD&from=2025-05-01T00&to=2025-05-06T00
```

Query parameters:
- `pair`: required. Example — `BTC/USD`
- `from`, `to`: optional. Format `YYYY-MM-DDTHH` (e.g. `2025-05-01T00`)

---

## Update Rates from CoinGecko

To fetch hourly exchange rates from CoinGecko:

```bash
make fetch
```

The command saves hourly BTC to USD/EUR/GBP rates into the database.
It won't duplicate if rates for the current hour already exist.

> Suggested cronjob for hourly sync:


