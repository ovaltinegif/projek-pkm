<p align="center"><a href="https://github.com/ovaltinegif/projek-pkm" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/ovaltinegif/projek-pkm/actions"><img src="https://img.shields.io/github/actions/workflow/status/ovaltinegif/projek-pkm/tests.yml?branch=main" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# DEN-Sentinel (Proyek PKM)

## Tentang DEN-Sentinel

DEN-Sentinel adalah sistem aplikasi pemantauan Demam Berdarah Dengue (DBD) berbasis *Internet of Things* (IoT) yang dibangun dengan sintaks ekspresif dan elegan dari kerangka kerja Laravel. Kami merancang sistem ini agar pengelolaan data lingkungan yang kompleks menjadi lebih terstruktur dan mudah dianalisis, dengan fitur-fitur seperti:

- [Pemantauan dasbor secara real-time](#).
- [Pemetaan sebaran risiko geografis interaktif dengan Leaflet JS](#).
- [Analisis tren suhu dan kelembaban harian menggunakan ApexCharts](#).
- [Algoritma kalkulasi tingkat risiko DBD yang berjalan otomatis](#).
- [Endpoint API IoT terintegrasi untuk komunikasi perangkat sensor](#).

DEN-Sentinel tangguh, mudah diakses, dan menyediakan alat yang dibutuhkan untuk mitigasi penyebaran nyamuk skala wilayah.

## Logika Kalkulasi Risiko

Sistem mengevaluasi ancaman secara objektif berdasarkan skor parameter lingkungan. Algoritma ini berjalan otomatis di `SensorController`:

* **Suhu (Bobot 30%)**: 30 poin (25-30°C ideal nyamuk); 10 poin (> 30°C).
* **Kelembaban (Bobot 30%)**: 30 poin (> 75%); 15 poin (> 60%).
* **Genangan/Hujan (Bobot 40%)**: 40 poin (Ketinggian air > 2m atau Curah Hujan > 10mm); 20 poin (Ada indikasi curah hujan/air).

*Klasifikasi Akhir: **Bahaya** (Skor ≥ 80), **Waspada** (Skor ≥ 50), **Aman** (Skor < 50).*

## IoT API Documentation

Perangkat keras (misalnya ESP32) dapat mengirimkan data lingkungan secara langsung ke sistem melalui jalur *endpoint* berikut:

**`POST /api/sensors/data`**

```json
{
    "device_id": "ESP-001",
    "temperature": 28.5,
    "humidity": 80.0,
    "rainfall": 12.5,
    "water_level": 5.0
}
