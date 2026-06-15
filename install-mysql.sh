#!/bin/bash
# MySQL/MariaDB 10.11 installer for WSL Ubuntu 24.04
# Use this if Docker pull times out or you prefer native MySQL
# Usage: wsl -d Ubuntu-24.04 -- bash install-mysql.sh
set -e

echo "=== Amar Store MySQL/MariaDB Setup ==="
echo ""

# Update apt cache (may take a while if network is slow)
echo "[1/5] Updating apt cache..."
sudo apt-get update 2>&1 | tail -2

# Install MariaDB 10.11 (MySQL-compatible, faster install)
echo "[2/5] Installing MariaDB Server..."
sudo DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends mariadb-server 2>&1 | tail -3

# Start the service (in WSL systemd or init.d)
echo "[3/5] Starting MariaDB service..."
if pidof systemd > /dev/null && [ "$(cat /proc/1/comm)" = "systemd" ]; then
    sudo systemctl start mariadb
    sudo systemctl enable mariadb
else
    sudo service mariadb start || sudo /etc/init.d/mariadb start
    # Auto-start on WSL init
    sudo bash -c 'cat > /etc/init.d/mariadb-autostart << EOF
#!/bin/sh -e
service mariadb start
EOF
sudo chmod +x /etc/init.d/mariadb-autostart
sudo update-rc.d mariadb-autostart defaults
fi

# Wait for the server to be ready
echo "[4/5] Waiting for MariaDB..."
for i in 1 2 3 4 5 6 7 8 9 10; do
    if sudo mysqladmin ping --silent 2>/dev/null; then
        echo "  MariaDB is running!"
        break
    fi
    sleep 1
done

# Create database and user
echo "[5/5] Creating database and user..."
sudo mysql <<'SQL'
CREATE DATABASE IF NOT EXISTS amar_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'amar'@'localhost' IDENTIFIED BY '***';
GRANT ALL PRIVILEGES ON amar_store.* TO 'amar'@'localhost';
FLUSH PRIVILEGES;
SELECT 'Database amar_store is ready!' AS status;
SQL

echo ""
echo "=== Setup complete! ==="
echo ""
echo "Test connection from Windows:"
echo "  wsl -d Ubuntu-24.04 -- bash -c 'mysql -u amar -p*** amar_store -e \"SELECT 1;\"'"
echo ""
echo "Or with host:port (note: WSL has its own IP, use 127.0.0.1 only if Windows can reach WSL port)"
echo "  127.0.0.1:3306  (if WSL port forwarding is enabled)"
echo "  or use Unix socket: /var/run/mysqld/mysqld.sock"
echo ""
echo "To enable port forwarding from Windows to WSL:"
echo "  wsl -d Ubuntu-24.04 -- bash -c 'sudo bash -c \"echo 1 > /proc/sys/net/ipv4/ip_forward\"'"
echo "  netsh interface portproxy add v4tov4 listenport=3306 listenaddress=0.0.0.0 connectport=3306 connectaddress=127.0.0.1"
