# =========================================
# Laravel AppSec Full Scan Script
# Fully Fixed & Optimized Version
# =========================================

Write-Host ""
Write-Host "=========================================="
Write-Host " Laravel AppSec Security Scan Started "
Write-Host "=========================================="
Write-Host ""

# -----------------------------------------
# Timestamp
# -----------------------------------------

$timestamp = Get-Date -Format "yyyy-MM-dd_HH-mm-ss"

# -----------------------------------------
# Reports Folder
# -----------------------------------------

$reportPath = "reports\$timestamp"

New-Item -ItemType Directory -Force -Path $reportPath | Out-Null

Write-Host "[OK] Reports folder created:"
Write-Host "$reportPath"

Write-Host ""

# -----------------------------------------
# Tool Check Function
# -----------------------------------------

function Check-Tool {
    param (
        [string]$tool
    )

    if (Get-Command $tool -ErrorAction SilentlyContinue) {
        Write-Host "[OK] $tool found"
        return $true
    }
    else {
        Write-Host "[ERROR] $tool NOT installed"
        return $false
    }
}

# -----------------------------------------
# Tool Checks
# -----------------------------------------

Write-Host "Checking tools..."
Write-Host ""

$semgrep   = Check-Tool "semgrep"
$composer  = Check-Tool "composer"
$trivy     = Check-Tool "trivy"
$gitleaks  = Check-Tool "gitleaks"
$snyk      = Check-Tool "snyk"

# -----------------------------------------
# Nuclei Path
# -----------------------------------------

$nucleiPath = "C:\nuclei\nuclei_3.8.0_windows_amd64 (1)\nuclei.exe"

if (Test-Path $nucleiPath) {
    Write-Host "[OK] Nuclei found"
    $nuclei = $true
}
else {
    Write-Host "[ERROR] Nuclei NOT found"
    $nuclei = $false
}

Write-Host ""

# =========================================
# SCANS
# =========================================

# -----------------------------------------
# Semgrep PHP Scan
# -----------------------------------------

if ($semgrep) {
    try {
        Write-Host "[1/8] Running Semgrep PHP Scan..."

        semgrep `
        --config=p/php `
        --exclude vendor `
        --exclude node_modules `
        . > "$reportPath\semgrep-php.txt"

        Write-Host "[OK] Semgrep PHP scan completed"
    }
    catch {
        Write-Host "[ERROR] Semgrep PHP scan failed"
    }

    try {
        Write-Host ""
        Write-Host "[2/8] Running Semgrep OWASP Scan..."

        semgrep `
        --config=p/owasp-top-ten `
        --exclude vendor `
        --exclude node_modules `
        . > "$reportPath\semgrep-owasp.txt"

        Write-Host "[OK] Semgrep OWASP scan completed"
    }
    catch {
        Write-Host "[ERROR] Semgrep OWASP scan failed"
    }
}

# -----------------------------------------
# Composer Audit
# -----------------------------------------

if ($composer) {
    try {
        Write-Host ""
        Write-Host "[3/8] Running Composer Audit..."

        composer audit `
        > "$reportPath\composer-audit.txt"

        Write-Host "[OK] Composer audit completed"
    }
    catch {
        Write-Host "[ERROR] Composer audit failed"
    }
}

# -----------------------------------------
# Trivy Filesystem Scan
# -----------------------------------------

if ($trivy) {
    try {
        Write-Host ""
        Write-Host "[4/8] Running Trivy Filesystem Scan..."

        trivy fs `
        --skip-dirs vendor `
        --skip-dirs node_modules `
        . > "$reportPath\trivy-fs.txt"

        Write-Host "[OK] Trivy filesystem scan completed"
    }
    catch {
        Write-Host "[ERROR] Trivy filesystem scan failed"
    }

    try {
        Write-Host ""
        Write-Host "[5/8] Running Trivy Secret Scan..."

        trivy fs `
        --scanners secret `
        --skip-dirs vendor `
        --skip-dirs node_modules `
        . > "$reportPath\trivy-secret.txt"

        Write-Host "[OK] Trivy secret scan completed"
    }
    catch {
        Write-Host "[ERROR] Trivy secret scan failed"
    }
}

# -----------------------------------------
# Gitleaks
# -----------------------------------------

if ($gitleaks) {
    try {
        Write-Host ""
        Write-Host "[6/8] Running Gitleaks..."
        
        # Note: Check reports/gitleaks.txt after run. If false positives exist, create a .gitleaksignore file.
        gitleaks detect `
        --no-git `
        > "$reportPath\gitleaks.txt"

        Write-Host "[OK] Gitleaks scan completed"
    }
    catch {
        Write-Host "[ERROR] Gitleaks scan failed"
    }
}

# -----------------------------------------
# Snyk (FIXED: Added detection-depth to prevent vendor scanning errors)
# -----------------------------------------

if ($snyk) {
    try {
        Write-Host ""
        Write-Host "[7/8] Running Snyk..."

        snyk test `
        --all-projects `
        --detection-depth=2 `
        --exclude=vendor,node_modules `
        --skip-unresolved `
        > "$reportPath\snyk.txt"

        Write-Host "[OK] Snyk scan completed"
    }
    catch {
        Write-Host "[ERROR] Snyk scan failed"
    }
}

# -----------------------------------------
# Nuclei
# -----------------------------------------

if ($nuclei) {
    try {
        Write-Host ""
        Write-Host "[8/8] Running Nuclei..."

        & $nucleiPath `
        -u http://127.0.0.1:8000 `
        -severity medium,high,critical `
        -tags cve,exposure,misconfig `
        -rate-limit 50 `
        > "$reportPath\nuclei.txt"

        Write-Host "[OK] Nuclei scan completed"
    }
    catch {
        Write-Host "[ERROR] Nuclei scan failed"
    }
}

# =========================================
# DONE
# =========================================

Write-Host ""
Write-Host "=========================================="
Write-Host " Laravel AppSec Security Scan Finished "
Write-Host "=========================================="

Write-Host ""
Write-Host "Reports saved in:"
Write-Host "$reportPath"

Write-Host ""