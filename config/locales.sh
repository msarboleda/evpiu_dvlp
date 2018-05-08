#!/usr/bin/env bash

echo ""
echo "Configurando lenguaje de Español - Colombia en el sistema..."
sudo locale-gen es_CO.utf8 >/dev/null 2>&1
sudo localectl set-locale LANG=es_CO.utf8
sudo su
echo LANGUAGE=es_CO.utf8 >> /etc/default/locale
echo LC_CTYPE=es_CO.utf8 >> /etc/default/locale
echo LC_ALL=es_CO.utf8 >> /etc/default/locale
echo "... Español - Colombia adaptado al sistema correctamente."