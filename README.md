# Febas DynDNS PHP Client für Speedport Smart 4

Dieses kleine PHP-Skript ist als DynDNS-Update-Client für den Router **Speedport Smart 4** gedacht. Es arbeitet mit dem DynDNS‑Dienst von [febas.de](https://www.febas.de). Das Skript wurde für PHP 8.4 entwickelt und erlaubt es dem Router, die eigene IP-Adresse über einfache HTTP-Anfragen an febas zu melden.

## Eigenschaften

- Kompatibel mit IPv4 und IPv6
- Authentifizierung per HTTP-Basic oder Query-Parameter
- Einfache API-Anbindung an `https://www.febas.de/api/dyndns.php`
- Fehlerbehandlung für ungültige Anfragen und DNS-Fehler

## Voraussetzungen

- PHP 8.4 oder neuer
- Erweiterung `curl` aktiviert
- Webserver (z. B. Apache, Nginx) zur Ausführung des Skripts

## Installation

1. `update.php` und `.htaccess` in das Dokumentenverzeichnis des Webservers hochladen.
2. Optional: Berechtigungen und Sicherheitsmaßnahmen (z. B. Zugriffs­beschränkung) konfigurieren.

## Verwendung

### Konfiguration im Speedport Smart 4

Wenn Sie den Router einrichten, verwenden Sie folgende Werte:

- **Hostname** – die Domain/Adresse, die aktualisiert werden soll
- **Benutzername** – Ihre `kundenid` bei febas
- **Passwort** – Ihr `token` bei febas
- **Updateserver-Adresse** – z. B. `https://deinedomain.de`

Nachdem die Einstellungen gespeichert sind, sendet der Speedport periodisch DNS‑Updates an das Skript.

### Mögliche Antworten

- `good` – Update erfolgreich
- `badauth` – Authentifizierungsfehler
- `dnserr` – DNS- oder API-Fehler
- `notfqdn` – Kein Hostname angegeben

HTTP-Statuscodes werden entsprechend gesetzt (z. B. 401 für `badauth`).


## Lizenz

Dieses Projekt steht unter der [MIT-Lizenz](LICENSE).

## Hinweise

- Achten Sie darauf, dass sensible Zugangsdaten nicht in Logs oder im Browser-Verlauf landen.

---