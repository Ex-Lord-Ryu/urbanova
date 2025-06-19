@extends('layouts.landing')

@section('title', 'Upload Bukti Pembayaran')

@push('css')
    <style>
        /* Main container styling */
        .payment-proof-page {
            padding: 40px 0;
            background-color: #f8f9fa;
            min-height: 70vh;
        }

        .payment-proof-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .payment-proof-title {
            font-size: 28px;
            font-weight: 700;
            color: #34395e;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .payment-proof-title .back-link {
            font-size: 16px;
            color: #090969;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .payment-proof-title .back-link i {
            margin-right: 5px;
        }

        /* Card styling */
        .payment-proof-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 24px;
            border: none;
        }

        .payment-proof-header {
            background: linear-gradient(135deg, #090969 0%, #1a1a9c 100%);
            color: white;
            padding: 20px;
            position: relative;
        }

        .payment-proof-body {
            padding: 20px;
        }

        /* Order info styling */
        .order-number {
            font-weight: 700;
            font-size: 20px;
            color: #fff;
            margin-bottom: 5px;
        }

        .order-date {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
        }

        .order-amount {
            font-weight: 700;
            font-size: 18px;
            color: #090969;
            margin-bottom: 20px;
        }

        /* Form styling */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #34395e;
            margin-bottom: 8px;
            display: block;
        }

        .form-text {
            color: #6c757d;
            font-size: 14px;
            margin-top: 5px;
        }

        .file-upload-wrapper {
            position: relative;
            width: 100%;
            height: 200px;
            border: 2px dashed #d4d4ff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .file-upload-wrapper:hover {
            background-color: #f1f3ff;
            border-color: #090969;
        }

        .file-upload-wrapper.file-selected {
            border: 2px solid #28a745;
            background-color: #fff;
        }

        .file-upload-input {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 10;
        }

        .file-upload-message {
            text-align: center;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .file-upload-icon {
            font-size: 40px;
            color: #090969;
            margin-bottom: 10px;
        }

        .file-upload-preview {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            z-index: 5;
            padding: 10px;
            background-color: #fff;
            transition: opacity 0.3s ease;
        }

        #file-overlay {
            background-color: rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        /* Button styling */
        .btn-upload {
            background: linear-gradient(135deg, #090969 0%, #1a1a9c 100%);
            color: #fff;
            border-radius: 50px;
            font-weight: 600;
            padding: 10px 30px;
            transition: all 0.3s;
            border: none;
            display: inline-block;
            text-decoration: none;
        }

        .btn-upload:hover {
            background: linear-gradient(135deg, #080851 0%, #13138a 100%);
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(9, 9, 105, 0.3);
            color: #fff;
        }

        .btn-upload i {
            margin-right: 5px;
        }

        /* Bank details section */
        .bank-details {
            background-color: #f1f3ff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .bank-details-title {
            font-size: 16px;
            font-weight: 600;
            color: #34395e;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .bank-details-title i {
            color: #090969;
            margin-right: 10px;
        }

        .bank-item {
            margin-bottom: 8px;
            display: flex;
        }

        .bank-label {
            font-weight: 600;
            color: #6c757d;
            width: 120px;
        }

        .bank-value {
            font-weight: 600;
            color: #34395e;
        }

        /* Success indicator styling */
        .upload-success {
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* File name display styling */
        #file-name-display {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 767px) {
            .payment-proof-title {
                font-size: 24px;
                flex-direction: column;
                align-items: flex-start;
            }

            .payment-proof-title .back-link {
                margin-bottom: 10px;
            }
        }
    </style>
@endpush

@section('content')
    <main class="payment-proof-page">
        <div class="payment-proof-container">
            <div class="payment-proof-title">
                <a href="{{ route('landing.orders.detail', $order->order_number) }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Kembali ke Detail Pesanan
                </a>
                <h2>Upload Bukti Pembayaran</h2>
            </div>

            <div class="payment-proof-card">
                <div class="payment-proof-header">
                    <div class="order-number">Pesanan #{{ $order->order_number }}</div>
                    <div class="order-date">{{ $order->created_at->format('d F Y, H:i') }}</div>
                </div>

                <div class="payment-proof-body">
                    <div class="order-amount">
                        Total Pembayaran: Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </div>

                    <div class="bank-details">
                        <div class="bank-details-title">
                            <i class="fas fa-university"></i> Informasi Bank
                        </div>
                        <p>Silakan transfer pembayaran ke rekening berikut:</p>

                        <div class="bank-item">
                            <span class="bank-label">Bank:</span>
                            <span class="bank-value">Bank Central Asia (BCA)</span>
                        </div>
                        <div class="bank-item">
                            <span class="bank-label">Atas Nama:</span>
                            <span class="bank-value">PT Urbanova Indonesia</span>
                        </div>
                        <div class="bank-item">
                            <span class="bank-label">No. Rekening:</span>
                            <span class="bank-value">8720145678</span>
                        </div>
                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('payment.upload', $order->order_number) }}" method="POST"
                        enctype="multipart/form-data" id="upload-form">
                        @csrf
                        <div class="form-group">
                            <label for="payment_proof" class="form-label">Upload Bukti Pembayaran</label>

                            <div class="file-upload-wrapper">
                                <input type="file" name="payment_proof" id="payment_proof" class="file-upload-input"
                                    accept="image/jpeg,image/png" required>
                                <div class="file-upload-message">
                                    <div class="file-upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <p>Klik atau seret file bukti pembayaran di sini</p>
                                    <p class="form-text">Format yang didukung: JPG, PNG (Maks. 2MB)</p>
                                </div>
                                <img id="preview-image" class="file-upload-preview">
                                <div id="upload-success" class="upload-success"
                                    style="display: none; position: absolute; bottom: 0; left: 0; right: 0; background-color: rgba(40, 167, 69, 0.9); color: white; padding: 10px; text-align: center; font-weight: bold; z-index: 20;">
                                    <i class="fas fa-check-circle mr-2"></i> Gambar berhasil dipilih dan siap diupload
                                </div>
                                <div id="file-overlay"
                                    style="display: none; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.3); z-index: 15; pointer-events: none;">
                                </div>
                            </div>

                            @error('payment_proof')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn-upload" id="submit-button">
                                <i class="fas fa-upload"></i> Upload Bukti Pembayaran
                            </button>
                            <div id="loading-indicator" style="display: none; margin-top: 15px;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="mt-2">Sedang mengupload bukti pembayaran...</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('payment_proof');
            const previewImage = document.getElementById('preview-image');
            const externalPreview = document.getElementById('external-preview');
            const externalPreviewImage = document.getElementById('external-preview-image');
            const uploadMessage = document.querySelector('.file-upload-message');
            const uploadSuccess = document.getElementById('upload-success');
            const uploadForm = document.getElementById('upload-form');
            const submitButton = document.getElementById('submit-button');
            const loadingIndicator = document.getElementById('loading-indicator');
            const fileOverlay = document.getElementById('file-overlay');

            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    const fileName = this.files[0].name;

                    reader.onload = function(e) {
                        // Show preview image inside upload area
                        previewImage.src = e.target.result;
                        previewImage.style.display = 'block';
                        uploadMessage.style.display = 'none';
                        fileOverlay.style.display = 'block';
                        uploadSuccess.style.display = 'block';

                        // Show external preview
                        externalPreviewImage.src = e.target.result;
                        externalPreview.style.display = 'block';

                        // Pastikan gambar terlihat jelas
                        previewImage.style.opacity = '0.8';
                        previewImage.style.zIndex = '5';

                        // Add a visual feedback animation
                        const wrapper = document.querySelector('.file-upload-wrapper');
                        wrapper.style.borderColor = '#28a745';
                        wrapper.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.5)';
                        wrapper.classList.add('file-selected');

                        // Scroll to external preview
                        setTimeout(function() {
                            externalPreview.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }, 300);
                    }

                    reader.onerror = function(error) {
                        console.error('FileReader error:', error);
                    }

                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Show loading indicator on form submission
            uploadForm.addEventListener('submit', function(event) {
                if (fileInput.files && fileInput.files[0]) {
                    const fileName = fileInput.files[0].name;
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengupload...';
                    loadingIndicator.style.display = 'block';

                    // Add a success message with animation
                    try {
                        const uploadWrapper = document.querySelector('.payment-proof-body');
                        const successMessage = document.createElement('div');
                        successMessage.className = 'alert alert-success mt-3';
                        successMessage.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sedang memproses bukti pembayaran Anda... <br><strong>File: </strong>' + fileName;
                        successMessage.style.opacity = '0';
                        successMessage.style.transition = 'opacity 0.5s';

                        uploadWrapper.insertBefore(successMessage, uploadForm);

                        setTimeout(function() {
                            successMessage.style.opacity = '1';
                        }, 100);
                    } catch (error) {
                        console.error('Error adding success message:', error);
                    }
                } else {
                    console.error('Form submitted but no file selected');
                    event.preventDefault();
                    alert('Silakan pilih file bukti pembayaran terlebih dahulu.');
                }
            });
        });
    </script>
@endpush
