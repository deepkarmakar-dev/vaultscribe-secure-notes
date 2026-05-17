# 🔐 VaultScribe

## Secure Laravel Platform with DevSecOps, Runtime Security & Infrastructure Hardening

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12-red?style=for-the-badge\&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.3-blue?style=for-the-badge\&logo=php)
![Docker](https://img.shields.io/badge/Docker-Containerized-2496ED?style=for-the-badge\&logo=docker)
![Azure](https://img.shields.io/badge/Azure-Cloud-0078D4?style=for-the-badge\&logo=microsoftazure)
![Security](https://img.shields.io/badge/Security-Hardened-success?style=for-the-badge)
![CI/CD](https://img.shields.io/badge/DevSecOps-Automated-orange?style=for-the-badge)

</div>

---

## 🌍 Live Demo

🔗 **[https://vaultscribe.in](https://vaultscribe.in)**

---

# 🚀 Overview

VaultScribe is a security-focused Laravel application designed to simulate a real-world hardened production environment.

The project combines:

* 🔐 Secure authentication & access control
* 🛡️ Web application security protections
* ☁️ Hardened cloud infrastructure
* 🐳 Dockerized deployment architecture
* 📊 Centralized monitoring & observability
* 🚨 Runtime threat detection
* ⚙️ Automated DevSecOps pipelines
* 🔍 Continuous vulnerability scanning

Unlike a traditional CRUD project, VaultScribe focuses heavily on:

* Defense-in-depth architecture
* Runtime security visibility
* Infrastructure hardening
* Operational monitoring
* Secure deployment workflows

---

# 🏗️ Infrastructure & Security Architecture

```text

                                  🌐 INTERNET
                                        │
                                        ▼
                     ┌────────────────────────────────┐
                     │          Cloudflare            │
                     │   DDoS • SSL • CDN • WAF Edge │
                     └──────────────┬─────────────────┘
                                    │
                                    ▼
                ┌──────────────────────────────────────────┐
                │         Azure Ubuntu 24.04 VM           │
                │         Hardened Production Host         │
                └──────────────────┬───────────────────────┘
                                   │
        ┌──────────────────────────┴──────────────────────────┐
        │                                                     │
        ▼                                                     ▼
┌──────────────────────┐                          ┌──────────────────────┐
│ UFW Firewall + NSG  │                          │    SSH Hardening     │
│ Port Restrictions    │                          │ Key Auth • Port 2222 │
└──────────┬───────────┘                          └──────────────────────┘
           │
           ▼
┌──────────────────────────────────────────────────────────────┐
│                     Nginx Reverse Proxy                     │
│             HTTPS • Security Headers • WAF                 │
│                  ModSecurity + OWASP CRS                   │
└─────────────────────────────┬────────────────────────────────┘
                              │
                              ▼
┌──────────────────────────────────────────────────────────────┐
│                        Docker Stack                         │
├──────────────────────────────────────────────────────────────┤
│  Laravel 12 Application                                     │
│  MySQL Database                                              │
│  Server-side Encryption                                      │
│  Centralized Logging                                         │
└─────────────────────────────┬────────────────────────────────┘
                              │
                              ▼
┌──────────────────────────────────────────────────────────────┐
│                 Monitoring & Threat Detection               │
├──────────────────────────────────────────────────────────────┤
│  Grafana      → Dashboards & Visualization                  │
│  Loki         → Centralized Log Aggregation                 │
│  Promtail     → Log Shipping                                │
│  Fail2Ban     → Intrusion Prevention                        │
│  Falco        → Runtime Threat Detection                    │
│  Lynis        → Security Auditing                           │
│  RKHunter     → Rootkit Detection                           │
│  Email Alerts → Real-time Notifications                     │
└──────────────────────────────────────────────────────────────┘

```

---

# ✨ Core Security Highlights

<table>
<tr>
<td width="50%">

### 🔐 Authentication Security

* Secure session authentication
* OTP email verification
* Google Authenticator 2FA
* Session regeneration
* Secure logout handling
* Route protection

</td>
<td width="50%">

### 🛡️ Web Security

* SQL Injection protection
* XSS protection
* CSRF protection
* CSP headers
* Prepared statements
* Secure error handling

</td>
</tr>
<tr>
<td width="50%">

### ☁️ Infrastructure Security

* Hardened Ubuntu server
* UFW firewall rules
* Azure NSG filtering
* SSH hardening
* Cloudflare protection
* HTTPS enforcement

</td>
<td width="50%">

### 🚨 Runtime Security

* Falco runtime monitoring
* Fail2Ban intrusion prevention
* Rootkit detection
* Security auditing
* Centralized log monitoring
* Real-time alerting

</td>
</tr>
</table>

---

# 🔐 Authentication & Identity Security

VaultScribe implements multiple authentication security layers.

## Features

* Secure user registration
* Secure login system
* OTP email verification
* Google Authenticator based 2FA
* Session-based authentication
* Session regeneration after login
* Unauthorized dashboard access blocking
* Secure logout handling

---

# 🔒 Password Security

Password protection mechanisms include:

```text
✔ Argon2id Password Hashing
✔ Pepper-based Hardening
✔ Weak Password Detection
✔ Uppercase Requirement
✔ Lowercase Requirement
✔ Numeric Requirement
✔ Special Character Enforcement
✔ Minimum Password Length Validation
```

---

# 📝 Secure Notes System

Users can:

* Create notes
* Edit notes
* Soft delete notes
* Restore deleted notes
* Permanently delete notes

Security controls ensure:

* Users can only access their own notes
* Sensitive data is encrypted server-side
* Sessions remain isolated per user

---

# 🔐 Server-Side Encryption

Sensitive application data is encrypted before storage.

Protected data includes:

* Note titles
* Note descriptions
* 2FA secrets
* Authentication-related sensitive values

---

# 🛡️ Application Security Protections

VaultScribe includes layered protections against common web attacks.

## Protected Against

```text
✔ SQL Injection (SQLi)
✔ Cross-Site Scripting (XSS)
✔ CSRF Attacks
✔ Clickjacking
✔ MIME Sniffing
✔ Information Disclosure
```

## Security Controls

* Content Security Policy (CSP)
* Secure HTTP headers
* Prepared statements
* Blade escaping
* Input validation
* CSRF middleware
* Sanitized production error messages

---

# 🧱 Web Application Firewall (WAF)

## ModSecurity + OWASP CRS

VaultScribe integrates ModSecurity with Nginx using the OWASP Core Rule Set.

### Protection Includes

* SQL injection detection
* XSS payload filtering
* RCE payload detection
* Automated exploit blocking
* Malicious request inspection
* Threat traffic filtering

---

# ☁️ Infrastructure & Deployment

## Infrastructure Stack

| Component       | Purpose                  |
| --------------- | ------------------------ |
| Azure Ubuntu VM | Hardened cloud server    |
| Docker          | Containerized deployment |
| Nginx           | Reverse proxy            |
| Cloudflare      | Edge protection          |
| MySQL           | Database                 |
| GitHub Actions  | CI/CD automation         |

---

# 🐳 Containerized Deployment

Docker is used for:

```text
✔ Laravel Application
✔ MySQL Database
✔ Grafana
✔ Loki
✔ Promtail
✔ Monitoring Services
```

Benefits:

* Infrastructure isolation
* Consistent deployment environments
* Simplified scaling & maintenance
* Improved portability

---

# 🔥 Firewall & SSH Hardening

## UFW Firewall

Configured for:

* Restricted inbound access
* HTTP/HTTPS filtering
* Controlled exposed services
* Firewall logging

---

## Azure NSG

Azure Network Security Groups provide:

* Cloud-level filtering
* Port-based access control
* Restricted network exposure

---

## SSH Hardening

```text
✔ Password Authentication Disabled
✔ SSH Key Authentication Only
✔ Custom SSH Port (2222)
✔ Reduced Brute-force Surface
```

---

# 🚨 Runtime Security & Threat Detection

## Fail2Ban

Automatically blocks:

* SSH brute-force attacks
* Repeated authentication failures
* Suspicious request patterns

---

## Falco Runtime Monitoring

Detects:

* Suspicious container activity
* Linux runtime anomalies
* Security policy violations
* Potential compromise behavior

---

## Lynis Security Auditing

Automated Linux hardening checks using scheduled cron jobs.

---

## RKHunter

Used for:

* Rootkit detection
* Integrity verification
* Host-level security monitoring

---

# 📊 Monitoring & Observability

Centralized observability stack:

```text
Grafana   → Dashboards & Visualization
Loki      → Centralized Log Aggregation
Promtail  → Log Shipping
Laravel   → Application Monitoring
Nginx     → Request Monitoring
Fail2Ban  → Security Event Visibility
Falco     → Runtime Security Alerts
UFW       → Firewall Activity Monitoring
```

---

# 📈 Monitoring Dashboards

Grafana dashboards include:

* Attack activity
* Security alerts
* SSH brute-force attempts
* Top attacking IPs
* Laravel logs
* Nginx request monitoring
* Firewall logs
* Runtime security events
* Lynis audit reports
* RKHunter security events

---

# 🔍 Real Attack Visibility

The monitoring stack captures real-world hostile traffic including:

```text
✔ .git/HEAD Probing
✔ PHPUnit Exploit Attempts
✔ Automated Reconnaissance Traffic
✔ Remote Code Execution Payloads
✔ Internet-wide Vulnerability Scans
```

This provides real-time visibility into internet attack behavior targeting the server.

---

# 📧 Alerting System

Integrated alerting includes:

* Security event notifications
* Runtime security alerts
* Authentication attack alerts
* Firewall event alerts
* Email-based notifications

Telegram alerting was also tested during development.

---

# ⚙️ CI/CD & DevSecOps Pipeline

## GitHub Actions Pipeline

```text
Developer Push
      │
      ▼
GitHub Actions Pipeline
      │
      ├── Laravel Testing
      ├── MySQL CI Environment
      ├── Docker Build
      ├── Trivy Container Scan
      ├── Snyk Vulnerability Scan
      ├── GitHub CodeQL Analysis
      ├── Gitleaks Secret Detection
      ├── SBOM Generation
      ├── Deployment Health Checks
      ▼
Azure Production Deployment
```

---

# 🔄 Automation & Maintenance

Automated cron jobs handle:

* Security audits
* Monitoring tasks
* Scheduled maintenance
* Log cleanup
* Recurring security checks

---

# 📦 Log Management & Rotation

Configured log rotation for:

* Nginx logs
* Laravel logs
* UFW firewall logs
* Fail2Ban logs
* Authentication logs

Benefits include:

* Reduced disk usage
* Long-term observability
* Stable monitoring pipelines
* Continuous security visibility

---

# 🛠️ Technology Stack

| Technology      | Usage                  |
| --------------- | ---------------------- |
| PHP 8.3         | Backend                |
| Laravel 12      | Framework              |
| MySQL 8         | Database               |
| Docker          | Containerization       |
| Nginx           | Reverse Proxy          |
| Cloudflare      | Edge Security          |
| Grafana         | Monitoring             |
| Loki            | Log Aggregation        |
| Promtail        | Log Shipping           |
| Fail2Ban        | Intrusion Prevention   |
| Falco           | Runtime Security       |
| ModSecurity     | WAF                    |
| OWASP CRS       | WAF Rules              |
| UFW             | Linux Firewall         |
| Azure NSG       | Network Filtering      |
| Trivy           | Container Scanning     |
| Snyk            | Vulnerability Scanning |
| CodeQL          | Static Analysis        |
| Gitleaks        | Secret Detection       |
| GitHub Actions  | CI/CD                  |
| Microsoft Azure | Cloud Hosting          |

---

# 🎯 Project Objective

VaultScribe was built to simulate a secure production-grade environment instead of a traditional CRUD application.

The project focuses on:

* Defense-in-depth security
* DevSecOps automation
* Infrastructure hardening
* Runtime threat visibility
* Secure deployment workflows
* Monitoring & operational visibility

---

# 🚀 Future Improvements

Planned improvements include:

* Kubernetes deployment
* SIEM integration
* OpenTelemetry tracing
* Multi-node scalability
* Automated incident response
* Secret rotation automation

---

# 🤝 Contributions

Security improvements, pull requests, and architecture suggestions are welcome.

---

# 📄 License

Licensed under the MIT License.

---

# 👨‍💻 Author

## Deep Karmakar

Security-focused Laravel & DevSecOps Developer
