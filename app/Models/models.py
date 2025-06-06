from django.db import models
from django.contrib.auth.models import User

class Arduino(models.Model):
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    nombre = models.CharField(max_length=100)
    ip = models.CharField(max_length=100)

    def __str__(self):
        return self.nombre

class Trigger(models.Model):
    arduino = models.ForeignKey(Arduino, on_delete=models.CASCADE, related_name='triggers')
    nombre = models.CharField(max_length=100)
    contexto = models.CharField(max_length=255)

    def __str__(self):
        return f"{self.nombre} ({self.arduino.nombre})"