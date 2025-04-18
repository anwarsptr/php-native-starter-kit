## 🙌 Support & Donations

**PHP Native Starter Kit** is an open-source project aimed at helping developers kickstart PHP application development quickly, cleanly, and in a well-structured manner — all without a framework.

If you find this project helpful and would like to support its ongoing development, you can show your appreciation through a donation. Your support means a lot and helps keep the project alive and growing!

☕ Support me via: <br/>
&nbsp; 👉 [💸 PayPal](https://paypal.me/anwarsptr) <br/>
&nbsp; 👉 [💖Bank Transfer / Virtual Account](https://anwarsptr.com/profile#paymentMethod)

Thank you so much for your support! 🙏

---

## 🗄️ How to Import the MySQL Database

To run this application, you need to import the provided database file named `db.sql`, located in the `database/` folder.

### 🔹 Using phpMyAdmin

1. Open **phpMyAdmin** in your browser.
2. Create a new database, for example: `php_native_db`.
3. Select the newly created database, then go to the **"Import"** tab.
4. Click **"Choose File"** and select the `db.sql` file from the `database/` folder.
5. Click the **"Go"** button to start the import process.

### 🔹 Using Command Line

If you're using a terminal or command prompt, run the following command:

```bash
mysql -u root -p php_native_db < database/db.sql
