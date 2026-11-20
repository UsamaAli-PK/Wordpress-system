  ──────
  2. URLs & Account Details
   Access Point                                               │ URL
  ────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────────────
   Public Live URL                                            │ website.n8nbyusama.tech[1]
   Public Admin Login                                         │ website.n8nbyusama.tech[2]
   Local Host URL                                             │ http://localhost:8090
   Local Admin Login                                          │ http://localhost:8090/wp-login.php
   Username                                                   │ wpadmin
   Password                                                   │ WpSecret2026!Pro

  [1]: website.n8nbyusama.tech https://website.n8nbyusama.tech
  [2]: website.n8nbyusama.tech https://website.n8nbyusama.tech/wp-login.php
  ──────
  3. Database Details for Newly Created WordPress

  The new instance uses its own dedicated, isolated MariaDB 11.4 container (wptest_prod_db):

   Parameter                                                  │ Value
  ────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────────────
   Container Name                                             │ wptest_prod_db
   Database Name                                              │ wptest
   Database User                                              │ wptest
   Database Password                                          │ WpTestPass2026_Secure!
   Internal Docker Host                                       │ db:3306 (from inside the wptest_prod_wp_internal network)
   Data Volume                                                │ wptest_prod_db_data

  (Your preexisting wordpress_db container and database remain separate and unaffected).
  ──────
  4. How to Access the Database

  Option A: Direct Interactive MariaDB Shell (Instant CLI)

  Run this command from your terminal to drop directly into the MariaDB interactive client:

    docker exec -it wptest_prod_db mariadb -u wptest -pWpTestPass2026_Secure! wptest

  Option B: Execute Single SQL Queries

    docker exec wptest_prod_db mariadb -u wptest -pWpTestPass2026_Secure! wptest -e "SHOW TABLES;"

  Option C: Access via WP-CLI

    cd /root/Desktop/learn/Wp-test
    docker compose run --rm cli wp db cli

  Option D: Export a Database Backup Dump

    docker exec wptest_prod_db mariadb-dump -u wptest -pWpTestPass2026_Secure! wptest >
    /root/Desktop/learn/Wp-test/wptest_backup.sq
