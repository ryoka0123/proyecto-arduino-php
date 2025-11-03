FROM python:3.12-slim

ENV PYTHONDONTWRITEBYTECODE=1
ENV PYTHONUNBUFFERED=1

RUN apt-get update && apt-get install -y curl unzip && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://raw.githubusercontent.com/arduino/arduino-cli/master/install.sh | BINDIR=/usr/local/bin sh

WORKDIR /app/core

COPY requirements.txt /app/core/
RUN pip install --no-cache-dir -r requirements.txt

COPY . /app/core

RUN arduino-cli config init && \
    arduino-cli core update-index && \
    arduino-cli core install esp32:esp32 && \
    arduino-cli core install arduino:avr && \
    arduino-cli core update-index && \
    arduino-cli core install esp32:esp32

EXPOSE 8001

CMD ["uvicorn", "app.main:app", "--host", "0.0.0.0", "--port", "8001"]
