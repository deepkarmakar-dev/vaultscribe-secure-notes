````markdown
# 🔐 VaultScribe – Secure Notes Application

VaultScribe is a security-focused Laravel application designed for secure note management, infrastructure hardening, runtime monitoring, and DevSecOps automation.

The project combines application security, cloud security, attack visibility, containerized deployment, centralized logging, and automated CI/CD pipelines to create a production-ready secure environment.

---

# 🏗️ Infrastructure & Security Architecture

```text
                           ┌──────────────────────┐
                           │      Internet        │
                           └──────────┬───────────┘
                                      │
                                      ▼
                         ┌────────────────────────┐
                         │      Cloudflare        │
                         │  DDoS / Edge Security  │
                         │ SSL / Request Filter   │
                         └──────────┬─────────────┘
                                    │
                                    ▼
                    ┌────────────────────────────────┐
                    │        Azure Virtual VM        │
                    │         Ubuntu 24.04           │
                    └──────────────┬─────────────────┘
                                   │
                    ┌──────────────▼──────────────┐
                    │        UFW Firewall         │
                    │       Azure NSG Rules       │
                    └──────────────┬──────────────┘
                                   │
                                   ▼
                    ┌─────────────────────────────┐
                    │     Nginx Reverse Proxy     │
                    │     + ModSecurity WAF       │
                    │       + OWASP CRS           │
                    └──────────────┬──────────────┘
                                   │
                 ┌─────────────────┴─────────────────┐
                 │                                   │
                 ▼                                   ▼
     ┌─────────────────────┐          ┌─────────────────────┐
     │ Laravel Application │          │ Monitoring Stack    │
     │   Docker Container  │          │ Grafana / Loki      │
     │                     │          │ Promtail            │
     └──────────┬──────────┘          └──────────┬──────────┘
                │                                │
                ▼                                ▼
      ┌────────────────────┐         ┌─────────────────────┐
      │   MySQL Database   │         │ Security Monitoring │
      │ Server-side        │         │ Falco / Fail2Ban    │
      │ Encryption         │         │ Lynis / RKHunter    │
      └────────────────────┘         └─────────────────────┘
````

---

# 🚀 Core Features

## Authentication System

* User registration
* Secure login system
* Forgot password functionality
* OTP email verification after registration
* Logout protection
* Unauthorized dashboard access blocked
* Session-based authentication
* Secure password validation

---

# 🔐 Password Security

Implemented protections include:

* Argon2id password hashing
* Pepper-based password hardening
* Weak password detection
* Uppercase requirement
* Lowercase requirement
* Numeric requirement
* Special character enforcement
* Minimum password length validation

---

# 📧 OTP Verification System

After registration:

* OTP is generated
* OTP is emailed to the user
* OTP expires automatically
* OTP attempts are tracked
* Registration completes only after verification

---

# 🔐 Two-Factor Authentication (2FA)

VaultScribe includes Google Authenticator based 2FA.

Features include:

* QR code generation
* OTP verification
* Encrypted 2FA secret handling
* Login challenge verification
* Enable / Disable 2FA
* Session-based 2FA validation

---

# 📝 Secure Notes System

Users can:

* Create notes
* Edit notes
* Soft delete notes
* Restore deleted notes
* Permanently delete notes

Access control ensures users can only access their own notes.

---

# 🔒 Server-Side Encryption

VaultScribe uses server-side encryption for sensitive application data.

Protected data includes:

* Note titles
* Note descriptions
* Authentication-related sensitive values
* 2FA secret storage

Security implementation includes:

* Laravel encryption mechanisms
* Encrypted database storage
* Secure handling of sensitive fields
* Protected server-side data processing

---

# 🔒 Access Control & Authorization

Security restrictions include:

* Dashboard access blocked without authentication
* Unauthorized route access protection
* User isolation for notes
* Session regeneration after login
* Secure logout handling

A user can only view and manage their own notes.

---

# 🛡️ Web Security Protections

VaultScribe includes protection against common web attacks including:

* SQL Injection (SQLi)
* Cross-Site Scripting (XSS)
* CSRF attacks
* Clickjacking
* MIME sniffing attacks
* Information disclosure

Security protections implemented:

* Content Security Policy (CSP)
* Secure HTTP headers
* Input validation
* Blade escaping
* Prepared statements
* CSRF middleware
* Sanitized production error messages

Production errors are configured to avoid revealing internal system information or attack clues.

---

# 🧱 Web Application Firewall (WAF)

## ModSecurity + OWASP CRS

VaultScribe uses ModSecurity integrated with Nginx and OWASP Core Rule Set (CRS).

Protection includes:

* SQL Injection detection
* XSS payload blocking
* Remote code execution payload detection
* Malicious request filtering
* Automated exploit blocking
* Request inspection & filtering

The monitoring stack captures real attack traffic and blocked exploit attempts.

---

# ☁️ Infrastructure & Deployment

Infrastructure stack includes:

* Microsoft Azure Virtual Machine
* Ubuntu 24.04 LTS
* Docker containerized deployment
* Nginx reverse proxy
* Cloudflare edge protection
* MySQL database server
* GitHub Actions CI/CD

---

# 🐳 Containerized Deployment

Docker is used for:

* Application deployment
* Monitoring services
* Logging stack
* Infrastructure isolation

Containerized services include:

* Laravel application
* Grafana
* Loki
* Promtail
* Monitoring stack services

---

# 🌐 Reverse Proxy & Edge Security

## Nginx

Configured for:

* Reverse proxy routing
* HTTPS handling
* Security headers
* Request filtering
* Optimized request handling

---

## Cloudflare

Provides:

* DDoS protection
* SSL/TLS edge protection
* CDN acceleration
* Request filtering
* Edge security protection

---

# 🔥 Firewall & SSH Hardening

## UFW Firewall

Configured for:

* HTTP/HTTPS filtering
* Restricted inbound access
* Firewall logging
* Controlled exposed services

---

## Azure NSG

Azure Network Security Groups configured for:

* Restricted network exposure
* Port-based filtering
* Cloud-level traffic control

---

## SSH Hardening

Server SSH security includes:

* Password login disabled
* SSH key-based authentication only
* Custom SSH port configuration (2222)
* Reduced brute-force attack surface

SSH access does not allow password authentication.

---

# 🚨 Intrusion Detection & Runtime Security

## Fail2Ban

Automatic banning for:

* SSH brute-force attempts
* Repeated malicious authentication attempts
* Suspicious repeated requests

---

## Falco

Runtime security monitoring for:

* Suspicious container activity
* Linux runtime events
* Security anomaly detection
* Real-time threat monitoring

---

## RKHunter

Used for:

* Rootkit detection
* Integrity verification
* Host-level security scanning

---

## Lynis

Linux security auditing configured using scheduled cron jobs.

Used for:

* Automated security auditing
* Linux hardening checks
* Recurring security assessments

---

# 📊 Monitoring, Logging & Observability

Centralized monitoring stack includes:

* Grafana dashboards
* Loki log aggregation
* Promtail log shipping
* Laravel log monitoring
* Nginx request monitoring
* UFW firewall monitoring
* Fail2Ban monitoring
* Runtime security event monitoring

---

# 📈 Monitoring Dashboards

Grafana dashboards include:

* Attack Activity
* Security Alerts
* Email Alerts
* Fail2Ban Active Bans
* Laravel Logs
* Nginx Request Activity
* SSH Brute Force Attempts
* Top Attacking IPs
* UFW Firewall Logs
* Falco Runtime Events
* Lynis Security Audits
* RKHunter Security Events

---

# 📧 Alerting System

Integrated alerting includes:

* Email security alerts
* Attack notifications
* Runtime security alerts
* Authentication attack alerts
* Firewall event notifications

Telegram alerting was previously tested during development.

---

# 🔍 Real Attack Visibility

Monitoring stack captures real attack attempts including:

* `.git/HEAD` probing
* PHPUnit exploit attempts
* Remote code execution payloads
* Automated exploit scanning
* Bot reconnaissance traffic

This provides real-time visibility into hostile internet traffic targeting the server.

---

# ⚙️ Local Development & Testing

VaultScribe is locally tested using:

* Laravel Herd
* Local MySQL environment
* Local security validation
* Manual penetration-style testing

Workflow:

1. Local development using Herd
2. Local testing & validation
3. Push to GitHub
4. CI/CD pipeline execution
5. Docker build process
6. Trivy container scanning
7. Security scanning
8. Deployment to Azure VM

---

# ⚙️ CI/CD & DevSecOps Pipeline

GitHub Actions pipeline includes:

* Automated Laravel testing
* MySQL CI environment
* Docker container build
* Trivy container scanning
* Snyk vulnerability scanning
* GitHub CodeQL analysis
* Gitleaks secret scanning
* SBOM generation
* Deployment health checks
* Rollback workflow support

---

# 🔄 Automation & Maintenance

Automated cron jobs are used for:

* Lynis audits
* Monitoring tasks
* Scheduled maintenance
* Security checks
* Log cleanup

---

# 📦 Log Management & Rotation

Linux log rotation configured for:

* Nginx access logs
* Nginx error logs
* Laravel logs
* UFW logs
* Fail2Ban logs
* Authentication logs

Benefits include:

* Reduced disk usage
* Long-term log retention
* Stable monitoring pipelines
* Continuous observability support

---

# 🛠️ Technology Stack

| Technology         | Usage                    |
| ------------------ | ------------------------ |
| PHP 8.3            | Backend                  |
| Laravel 12         | Framework                |
| MySQL 8            | Database                 |
| Docker             | Containerization         |
| Nginx              | Reverse Proxy            |
| GitHub Actions     | CI/CD                    |
| Trivy              | Container Scanning       |
| Microsoft Azure VM | Cloud Hosting            |
| Ubuntu 24.04       | Server OS                |
| Cloudflare         | Edge Security            |
| Grafana            | Monitoring Dashboard     |
| Loki               | Log Aggregation          |
| Promtail           | Log Collection           |
| Falco              | Runtime Security         |
| Fail2Ban           | Intrusion Prevention     |
| Lynis              | Security Auditing        |
| RKHunter           | Rootkit Detection        |
| ModSecurity        | Web Application Firewall |
| OWASP CRS          | WAF Rule Set             |
| UFW                | Linux Firewall           |
| Azure NSG          | Cloud Network Filtering  |
| Snyk               | Vulnerability Scanning   |
| CodeQL             | Static Security Analysis |
| Gitleaks           | Secret Detection         |

---

# 🧪 Security Philosophy

VaultScribe follows a defense-in-depth security model using multiple security layers including:

* Cloudflare edge filtering
* Azure NSG filtering
* UFW firewall rules
* Nginx hardening
* ModSecurity WAF
* OWASP CRS rules
* Fail2Ban intrusion prevention
* Falco runtime monitoring
* Secure Laravel middleware
* Centralized monitoring & alerting

---

# 🤝 Contribution

Pull requests and security improvements are welcome.

If you discover vulnerabilities or want to improve the security architecture, contributions are appreciated.

---

# 📄 License

Licensed under the MIT License.

---

# 👨‍💻 Author

**Deep Karmakar**
Security-focused Laravel & DevSecOps Developer

```
```
