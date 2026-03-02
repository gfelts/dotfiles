# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Environment

- **OS**: Debian-based Linux on Proxmox VE (kernel 6.8.12-17-pve)
- **Shell**: bash
- **User tools**: Claude Code (`~/.local/bin/claude`), tmux, curl, apt
- **PATH**: `~/.local/bin` is prepended to PATH (set in `~/.bashrc`)

## Notes

This is a general-purpose home directory / dotfiles repo. The referral tracking app lives at `/var/www/referral` (moved from `~/referral`).

## Referral App

- **Location**: `/var/www/referral`
- **URL**: `http://referral.local` / `http://192.168.100.40`
- **Apache vhost**: `/etc/apache2/sites-available/referral.conf`
- **Database**: MariaDB — `referral_db` / user `referral`
- **Cache commands**: `cd /var/www/referral && sg www-data "php artisan config:cache && php artisan route:cache && php artisan view:cache"`
- **Migrations**: `cd /var/www/referral && php artisan migrate`
