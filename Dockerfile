FROM python:3.12-slim

ENV PYTHONDONTWRITEBYTECODE=1
ENV PYTHONUNBUFFERED=1

WORKDIR /app/core

COPY requirements.txt /app/core

RUN pip install --no-cache-dir -r requirements.txt

COPY . /app/core

EXPOSE 8001

CMD ["uvicorn", "app.main:app", "--host", "0.0.0.0", "--port", "8001", "--watch"]
