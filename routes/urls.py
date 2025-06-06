from django.contrib import admin
from django.urls import path
from . import views

urlpatterns = [
    # Página principal: Iniciar sesión
    path('', views.inicio_sesion, name='inicioSesion'),

    # Página principal del usuario después de iniciar sesión
    path('registro/', views.registro, name='registro'),

    # Registrar nuevo Arduino
    path('dashboard/arduino', views.microcontrolador, name='microcontrolador'),

    # Ver Arduino específico y sus triggers
    path('dashboard/registro_arduino/', views.registroArduino, name='registroArduino'),

    # Registrar Trigger para un Arduino
    path('dashboard/triggers/<int:arduino_id>/', views.triggers, name='triggers'),

    # Registrar Trigger para un Arduino
    path('dashboard/registro_trigger/<int:arduino_id>/', views.registroTriggers, name='registroTriggers'),

    path('dashboard/editar_trigger/<int:arduino_id>/<int:trigger_id>/', views.editar_trigger, name='editar_trigger'),

    path('dashboard/eliminar_trigger/<int:arduino_id>/<int:trigger_id>/', views.eliminar_trigger, name='eliminar_trigger'),

    path('cerrarSesion/', views.cerrar_sesion, name='cerrarSesion'),

    path('dashboard/eliminar_arduino/<int:arduino_id>/', views.eliminar_arduino, name='eliminar_arduino'),
]

handler404 = 'proyectoArduino.views.pagina_no_encontrada'
