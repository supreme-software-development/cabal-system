# Cabal Online Registration System

A simple and secure registration system for Cabal Online private servers.

## Requirements

- PHP 7.4 or higher
- SQL Server
- SQL Server Driver for PHP
- Following PHP extensions enabled:
  - sqlsrv
  - openssl
  - json

## Installation

1. Clone the repository or download the files to your web server directory.

2. Copy the environment file:
   ```bash
   cp .env.example .env
   ```

3. Edit the `.env` file with your database credentials:
   ```env
   DB_HOST=localhost     # Your SQL Server host
   DB_USER=sa           # Your database username
   DB_PASS=your_password # Your database password
   DB_NAME=ACCOUNT      # Your database name (usually ACCOUNT for Cabal)
   ```

4. Make sure your SQL Server is running and accessible with the provided credentials.

5. Update the following files according to your server:
   - `index.php`: 
     - Change title: `<title>Register | YOUR_SERVER_NAME</title>`
     - Update logo: Replace `images/logo-light.png` with your server logo
     - Update social media links in the footer
   - `images/`:
     - Add your logo files:
       - `logo-light.png` - Main logo
       - `favicon.png` - Light mode favicon
       - `favicon-light.png` - Dark mode favicon

## Features

- Secure registration with password hashing
- Email validation
- Username validation
- Password requirements:
  - Minimum 8 characters
  - Only alphanumeric characters allowed
- CSRF protection
- XSS protection
- SQL injection protection
- Responsive design
- Dark/Light mode favicon support

## File Structure

```
├── config/
│   └── database.php     # Database configuration
├── images/
│   ├── favicon.png
│   ├── favicon-light.png
│   └── logo-light.png
├── .env                 # Environment variables (not in git)
├── .env.example         # Example environment file
├── .gitignore          # Git ignore rules
├── process.php         # Registration processing
├── index.php        # Registration form
└── README.md           # This file
```

## Security

- All database credentials are stored in `.env` file
- Passwords are encrypted using SQL Server's PWDENCRYPT
- Input sanitization for all user inputs
- Prepared statements for all database queries
- Error messages don't expose sensitive information

## Common Issues

1. **Database Connection Error**
   - Verify SQL Server is running
   - Check credentials in `.env` file
   - Ensure SQL Server Driver for PHP is installed

2. **Missing Images**
   - Create `images` directory
   - Add required logo and favicon files

3. **PHP Version Error**
   - Check PHP version: `php -v`
   - Upgrade PHP if below 7.4

## Contributing

We welcome contributions to improve the registration system! Here's how you can help:

1. **Report Issues**
   - Use the GitHub Issues tab to report bugs
   - Include detailed steps to reproduce the issue
   - Mention your environment (PHP version, SQL Server version)

2. **Submit Pull Requests**
   - Fork the repository
   - Create a new branch for your feature/fix
   - Follow the existing code style
   - Add comments for new functions
   - Test your changes thoroughly
   - Submit a PR with a clear description of changes

3. **Feature Requests**
   - Use GitHub Issues to suggest new features
   - Explain the use case for the feature
   - Discuss potential implementation approaches

## Development

1. Never commit `.env` file
2. Use `.env.example` for template
3. Keep error logging enabled in development
4. Test all validation scenarios

## License

MIT License

Copyright (c) 2024 Your Name or Organization

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

## Support

For issues and support:
1. Check the [Common Issues](#common-issues) section
2. Search existing GitHub Issues
3. Create a new Issue if your problem isn't already reported
4. For security vulnerabilities, please email directly instead of creating a public issue

---
Made with ❤️ for the Cabal Online community 