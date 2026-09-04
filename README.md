# xyz-admarket

XYZ Admarket is a simple and feature rich PHP + MySQL based script which provides you a platform to launch an advertiser-publisher network or adserver site like Google AdWords/AdSense, Media Net etc. It supports Pay-Per-Click (PPC) text and banner formats for advertisers as well as customizable text/banner ad display codes for publishers.

## Requirements

Before installing, make sure your server meets the following requirements:

- **PHP ^8.2**

  - Output Buffering Enabled
  - Mbstring Extension
  - Curl Extension
  - URL fopen Enabled
  - GD Support
  - GZ Support
  - MySQL Extension
  - PDO Extension

- **Web Server** (e.g., Nginx, Apache)

- **MySQL Server**

## Installation

Follow these steps to set up:

1. **Clone the Repository:**

   ```bash
   git clone -b 7.x https://github.com/kishor-rajbanshi/xyz-admarket.git
   cd xyz-admarket
   ```

2. **Set Permissions:**
   Ensure the following directories have proper write permissions (chmod 777):

   - `/cache`
   - `/upload`
   - `/userfiles`
   - `/backup`

3. **Run Installation:**
   Open your web browser and navigate to `/installation`.
   Follow the on-screen instructions to complete the installation process.

4. **License Setup:**
   You'll need to set up the license from the member area of xyzscripts.com.

5. **Set Up Cron Jobs:**
   To automate certain tasks, such as data backups, you can set up cron jobs. Here are cron job commands that you can add to your server's crontab:

   - To run every minute:

    ```bash
    * * * * * wget -O /dev/null --quiet http://ip_or_domain/cron/index.php?page=minutecron/data-backup
    ```

   - To run every hour:

    ```bash
    0 * * * * wget -O /dev/null --quiet http://ip_or_domain/cron/index.php?page=datacron/data-backup
    ```
