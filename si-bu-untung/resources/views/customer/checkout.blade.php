<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout — Toko Kelontong Bu Untung</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
            color: #000000;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            border-bottom: 1px solid #e5e5e5;
        }

        .user-name {
            font-size: 24px;
            font-weight: 700;
            color: #000000;
        }

        .header-icons {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .icon-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: transparent;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            -webkit-tap-highlight-color: transparent;
            user-select: none;
            text-decoration: none;
            outline: none;
        }

        .icon-wrapper::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: rgba(255, 87, 34, 0.1);
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .icon-wrapper:hover {
            transform: translateY(-2px) scale(1.1);
            background: rgba(255, 87, 34, 0.05);
        }

        .icon-wrapper:hover::before {
            opacity: 1;
            transform: scale(1);
        }

        .icon-wrapper:active {
            transform: translateY(0) scale(0.95);
            transition: all 0.15s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .icon-wrapper:active::before {
            transform: scale(1.2);
            opacity: 0.3;
        }

        .icon {
            width: 45px;
            height: 45px;
            position: relative;
            z-index: 1;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            pointer-events: none;
        }

        .icon-wrapper:hover .icon {
            transform: scale(1.1) rotate(5deg);
        }

        .icon-wrapper:active .icon {
            transform: scale(0.9) rotate(-5deg);
        }

        /* Animasi bounce untuk profile icon saat hover */
        @keyframes bounceProfile {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-3px);
            }
        }

        .icon-wrapper.profile-icon:hover .icon {
            animation: bounceProfile 0.6s ease-in-out;
        }

        /* Profile Menu & Dropdown */
        .profile-menu {
            position: relative;
            display: inline-block;
        }

        .profile-dropdown {
            position: absolute;
            top: 55px;
            right: 0;
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            z-index: 1001;
            display: none;
            animation: fadeInDown 0.3s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-dropdown.active {
            display: block;
            z-index: 1001;
        }

        .profile-dropdown-item {
            padding: 14px 20px;
            color: #333;
            text-decoration: none;
            display: block;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            position: relative;
        }

        .profile-dropdown-item:last-child {
            border-bottom: none;
        }

        .profile-dropdown-item:hover {
            background: #f5f5f5;
            color: #000;
        }

        .profile-dropdown-item.logout {
            border-top: 1px solid #e5e5e5;
            background: #fff;
            color: #ff5722;
            border: 1px solid #ff5722;
            border-radius: 8px;
            margin: 8px;
            text-align: center;
            font-weight: 600;
        }

        .profile-dropdown-item.logout:hover {
            background: #ff5722;
            color: #fff;
        }

        .profile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
        }

        .profile-overlay.active {
            display: block;
        }

        .profile-menu.active .icon-wrapper {
            background: rgba(255, 87, 34, 0.1);
        }

        /* Style untuk button sebagai icon-wrapper */
        button.icon-wrapper {
            width: 45px;
            height: 45px;
            border: none;
            background: transparent;
            cursor: pointer;
            padding: 0;
            margin: 0;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Back Button */
        .back-button {
            padding: 20px 40px;
            background-color: #ffffff;
        }

        .back-arrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 18px;
            cursor: pointer;
            color: #000000;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .back-arrow:hover {
            transform: translateX(-4px);
        }

        .back-arrow img {
            width: 24px;
            height: 24px;
        }

        /* Main Container */
        .checkout-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 40px;
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }

        /* Left Column */
        .left-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #000000;
        }

        .section-box {
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 20px;
            position: relative;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .subsection-title {
            font-size: 15px;
            font-weight: 600;
            color: #000000;
        }

        .change-btn {
            background: none;
            border: 1px solid #ff5722;
            color: #ff5722;
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: all 0.2s;
        }

        .change-btn:hover {
            background: #ff5722;
            color: white;
            transform: translateY(-1px);
        }

        .detail-text {
            font-size: 14px;
            color: #000000;
            line-height: 1.6;
            margin-bottom: 8px;
        }

        .divider {
            height: 1px;
            background: #e5e5e5;
            margin: 12px 0;
        }

        /* Pengiriman Section */
        .delivery-info {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .delivery-info-compact {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 16px;
        }

        .delivery-info-compact .delivery-row {
            margin: 0;
        }

        @media (max-width: 768px) {
            .delivery-info-compact {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .delivery-info-compact .delivery-row[style*="grid-column"] {
                grid-column: 1 !important;
            }
        }

        .delivery-row {
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .delivery-icon {
            width: 16px;
            height: 16px;
            margin-top: 3px;
            flex-shrink: 0;
            opacity: 0.7;
        }

        .delivery-text {
            font-size: 13px;
            color: #000000;
            line-height: 1.4;
        }

        .delivery-text strong {
            font-weight: 600;
            display: inline-block;
            margin-right: 4px;
        }

        /* Product List */
        .product-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .product-item {
            display: grid;
            grid-template-columns: minmax(0, 2fr) auto auto;
            gap: 20px;
            padding: 20px;
            background-color: #F0EEED;
            border-radius: 20px;
            border: 1px solid #e2e2e2;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.3s;
        }

        .product-item:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
            background: #fff;
        }

        .item-image-wrapper {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 16px;
        }

        .product-item .product-image {
            width: 100%;
            height: auto;
            object-fit: contain;
            max-width: 140px;
            max-height: 140px;
            border-radius: 12px;
            transition: transform 0.25s ease;
        }

        .product-item:hover .product-image {
            transform: scale(1.15);
        }

        .item-name {
            font-size: 14px;
            font-weight: 500;
            color: #000000;
            text-align: left;
            max-width: 260px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }

        .item-controls {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-right: 80px;
        }

        .item-quantity {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Buttons */
        .btn-circle {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 10px;
            background: #fff;
            display: grid;
            place-items: center;
            cursor: pointer;
            padding: 0;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .06);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .btn-circle::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.06);
            border-radius: 10px;
            transform: scale(0);
            transition: transform .3s ease;
        }

        .btn-circle:hover {
            transform: translateY(-1px) scale(1.06);
            box-shadow: 0 8px 18px rgba(0, 0, 0, .14);
        }

        .btn-circle:active {
            transform: translateY(0) scale(0.98);
        }

        .btn-circle:active::before {
            transform: scale(1.8);
        }

        .btn-circle img {
            width: 30px;
            height: 30px;
            display: block;
            transition: transform .18s ease;
        }

        .btn-circle:hover img {
            transform: translateY(-1px) scale(1.04);
        }

        .btn-circle:active img {
            transform: translateY(0) scale(.99);
        }

        .btn-circle:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .btn-circle:disabled:hover {
            transform: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .06);
        }

        .quantity-value,
        .qty-value {
            font-size: 18px;
            font-weight: 600;
            min-width: 30px;
            text-align: center;
        }

        .item-actions {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 90px;
        }

        .item-price,
        .product-price {
            font-size: 18px;
            font-weight: 700;
            color: #ff5722;
            text-align: center;
            padding: 6px 14px;
            border: 1px solid #ff5722;
            border-radius: 999px;
            background: rgba(255, 87, 34, 0.06);
            box-shadow: 0 2px 4px rgba(255, 87, 34, 0.15);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .item-actions:hover .item-price,
        .item-actions:hover .product-price {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(255, 87, 34, 0.25);
        }

        .remove-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 10px;
            border-radius: 6px;
            transition: background-color 0.2s, transform 0.2s;
        }

        .remove-btn:hover {
            background-color: #fff5f5;
            transform: scale(1.1);
        }

        .remove-btn img {
            width: 24px;
            height: 24px;
        }

        /* Right Column - Summary */
        .summary-sidebar {
            position: sticky;
            top: 20px;
            height: fit-content;
        }

        .summary-card {
            background: #F5F5F5;
            border-radius: 16px;
            padding: 30px;
        }

        .summary-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #000000;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 14px;
            transition: transform 0.3s ease;
        }

        .summary-row:hover {
            transform: scale(1.02);
        }

        .summary-label {
            color: #666666;
        }

        .summary-value {
            font-weight: 600;
            color: #ff5722;
            transition: transform 0.3s ease;
        }

        .summary-divider {
            height: 1px;
            background: #d5d5d5;
            margin: 20px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            font-size: 16px;
            transition: transform 0.3s ease;
        }

        .total-row:hover {
            transform: scale(1.02);
        }

        .total-label {
            font-weight: 700;
            color: #000000;
        }

        .total-value {
            font-size: 20px;
            font-weight: 700;
            color: #000000;
            transition: transform 0.3s ease;
        }

        .checkout-btn {
            width: 100%;
            padding: 16px;
            background: #ff5722;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }

        .checkout-btn:hover:not(:disabled) {
            background: #e64a19;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 87, 34, 0.3);
        }

        .checkout-btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .modal.active {
            display: flex;
            animation: fadeInModal 0.3s ease;
        }

        .modal.closing {
            animation: fadeOutModal 0.4s ease forwards;
        }

        @keyframes fadeInModal {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes fadeOutModal {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            overflow-x: hidden;
            box-sizing: border-box;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .modal.active .modal-content {
            animation: slideInModalContent 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .modal.closing .modal-content {
            animation: slideOutModalContent 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes slideInModalContent {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes slideOutModalContent {
            from {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
            to {
                opacity: 0;
                transform: translateY(-20px) scale(0.98);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            box-sizing: border-box;
            width: 100%;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #666;
        }
        .delivery-benefit{
  display:flex; gap:12px; align-items:center;
  padding:12px 14px; border:1px solid #eaeaea; border-radius:10px; margin-bottom:16px; background:#fafafa;
  box-sizing: border-box;
  width: 100%;
  overflow: hidden;
}
.delivery-benefit img{ width:28px; height:28px }

.address-list{ display:flex; flex-direction:column; gap:12px; box-sizing: border-box; width: 100%; overflow: hidden; }

.addr-card{
  border:1px solid #e5e5e5; border-radius:12px; padding:16px;
  transition:border-color .2s, box-shadow .2s; position:relative; background:#fff;
  box-sizing: border-box;
  width: 100%;
  overflow: hidden;
}

.addr-card.new-address-card {
  animation: slideInCard 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes slideInCard {
  from {
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}
.addr-card.selected{ border-color:#ff5722; box-shadow:0 4px 16px rgba(255,87,34,.15); }

.addr-card.selecting {
  animation: pulseSelect 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes pulseSelect {
  0% {
    transform: scale(1);
    box-shadow: 0 4px 16px rgba(255,87,34,.15);
  }
  50% {
    transform: scale(1.02);
    box-shadow: 0 8px 24px rgba(255,87,34,.3);
  }
  100% {
    transform: scale(1);
    box-shadow: 0 4px 16px rgba(255,87,34,.15);
  }
}

.delivery-info-compact .delivery-row {
  transition: all 0.3s ease;
}

.delivery-info-compact .delivery-row.updating {
  animation: updateFlash 0.5s ease;
}

@keyframes updateFlash {
  0%, 100% {
    background-color: transparent;
  }
  50% {
    background-color: rgba(255, 87, 34, 0.1);
  }
}
.addr-card-header{ display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; }
.addr-label{ font-weight:600 }
.addr-badges{ display:flex; gap:8px; }
.badge{ font-size:12px; font-weight:700; padding:4px 8px; border-radius:8px; }
.badge-primary{ background:#ffe5e0; color:#d84315; }
.badge-selected{ background:#ff5722; color:#fff; }

.addr-name{ font-weight:700; margin-top:6px }
.addr-phone{ color:#333; margin:2px 0 6px }
.addr-note{ color:#666; font-size:13px; margin-bottom:6px }
.addr-full{ color:#111; line-height:1.5 }
.addr-pin{ display:inline-flex; gap:6px; align-items:center; margin-top:8px; color:#1e88e5; text-decoration:none; font-size:14px }
.addr-actions{ margin-top:10px; display:flex; justify-content:flex-end }
.addr-radio{ font-size:14px; font-weight:600; display:flex; gap:8px; align-items:center; cursor:pointer }

.delete-addr-btn, .set-default-btn {
  background: none;
  border: none;
  cursor: pointer;
  padding: 6px 10px;
  border-radius: 6px;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.delete-addr-btn {
  background: #fff5f5;
  border: 1px solid #fed7d7;
}

.delete-addr-btn:hover:not(:disabled) {
  background: #fed7d7;
  transform: scale(1.05);
}

.delete-addr-btn:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.set-default-btn {
  background: #fff5f0;
  border: 1px solid #ffccbc;
  color: #e64a19;
  font-size: 12px;
  font-weight: 600;
  font-family: 'Poppins', sans-serif;
}

.set-default-btn:hover {
  background: #ffccbc;
  transform: translateY(-1px);
}

.add-address-section{ 
  margin-top:20px; 
  padding-top:20px; 
  border-top:1px solid #e5e5e5; 
  box-sizing: border-box; 
  width: 100%; 
  overflow: hidden; 
}

.add-title{ 
  color:#333; 
  margin-bottom:12px; 
  font-size: 15px;
  font-weight: 600;
}

.add-btn{
  width:100%; 
  padding:14px 20px; 
  border:none; 
  border-radius:10px;
  background:#ff5722; 
  color:#fff; 
  font-weight:600; 
  font-size:15px;
  cursor:pointer;
  font-family: 'Poppins', sans-serif;
  box-sizing: border-box;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(255, 87, 34, 0.2);
}

.add-btn:hover{ 
  background:#e64a19; 
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
}

.add-btn:active{
  transform: translateY(0);
}

.add-form{ 
  margin-top:16px; 
  border:2px solid #ff5722; 
  border-radius:12px; 
  padding:24px; 
  background:#ffffff; 
  box-sizing: border-box; 
  width: 100%; 
  overflow: hidden;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
  animation: slideDown 0.3s ease;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.form-row{ 
  display:flex; 
  flex-direction:column; 
  gap:8px; 
  margin-bottom:18px; 
  box-sizing: border-box; 
  width: 100%; 
}

.form-row:last-child {
  margin-bottom: 0;
}

.form-row label {
  font-size: 14px;
  font-weight: 600;
  color: #333;
  margin-bottom: 4px;
  font-family: 'Poppins', sans-serif;
}

.form-row.inline{ 
  flex-direction:row; 
  gap:10px; 
  align-items:center;
  margin-bottom: 20px;
}

.form-row.inline label {
  margin-bottom: 0;
  font-weight: 500;
  cursor: pointer;
  user-select: none;
}

.form-row input,
.form-row textarea {
  box-sizing: border-box;
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e5e5e5;
  border-radius: 8px;
  font-size: 14px;
  font-family: 'Poppins', sans-serif;
  color: #333;
  background: #ffffff;
  transition: all 0.3s ease;
}

.form-row input:focus,
.form-row textarea:focus {
  outline: none;
  border-color: #ff5722;
  box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.1);
  background: #fff;
}

.form-row input::placeholder,
.form-row textarea::placeholder {
  color: #999;
}

.form-row input:hover,
.form-row textarea:hover {
  border-color: #ccc;
}

.form-row textarea {
  resize: vertical;
  min-height: 80px;
  line-height: 1.6;
}

.form-row input[type="checkbox"] {
  width: auto;
  margin: 0;
  cursor: pointer;
  accent-color: #ff5722;
  width: 18px;
  height: 18px;
  flex-shrink: 0;
}

.form-row input[type="checkbox"]:focus {
  box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.2);
}


        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #000;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #ff5722;
        }

        .save-btn {
            width: 100%;
            padding: 14px 20px;
            background: #ff5722;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            box-sizing: border-box;
            box-shadow: 0 2px 8px rgba(255, 87, 34, 0.3);
        }

        .save-btn:hover {
            background: #e64a19;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 87, 34, 0.4);
        }

        .save-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(255, 87, 34, 0.3);
        }

        /* Footer */
        .footer {
            background-color: #000000;
            color: #ffffff;
            padding: 60px 40px;
            margin-top: 60px;
        }

        .footer-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .footer-subtitle {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            margin-top: 40px;
        }

        .footer-text {
            font-size: 14px;
            line-height: 1.8;
            color: #cccccc;
            max-width: 900px;
        }

        /* Alert */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-error {
            background: #fed7d7;
            color: #742a2a;
            border: 1px solid #fc8181;
        }

        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border: 1px solid #68d391;
        }

        /* Success Notification Toast */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #48bb78;
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(72, 187, 120, 0.3);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 2000;
            font-weight: 600;
            font-size: 14px;
            min-width: 280px;
            opacity: 0;
            transform: translateX(400px) scale(0.9);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .toast-notification.show {
            opacity: 1;
            transform: translateX(0) scale(1);
        }

        .toast-notification.hide {
            opacity: 0;
            transform: translateX(400px) scale(0.9);
        }

        .toast-icon {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
            animation: checkmark 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes checkmark {
            0% {
                transform: scale(0) rotate(-45deg);
                opacity: 0;
            }
            50% {
                transform: scale(1.2) rotate(5deg);
            }
            100% {
                transform: scale(1) rotate(0deg);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(400px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
            to {
                opacity: 0;
                transform: translateX(400px) scale(0.9);
            }
        }

        /* Animations */
        @keyframes fadeOut {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(0.8); }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }

            .summary-sidebar {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }

            .user-name {
                font-size: 20px;
            }

            .checkout-container {
                padding: 20px;
            }

            .product-item {
                grid-template-columns: 1fr auto;
                grid-template-rows: auto auto;
                align-items: flex-start;
            }

            .item-image-wrapper {
                grid-column: 1 / 3;
            }

            .item-controls {
                justify-content: flex-start;
            }

            .item-actions {
                align-items: flex-end;
            }

            .remove-btn {
                padding: 14px;
                min-width: 48px;
                min-height: 48px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        @php
            $u = Auth::guard('pelanggan')->user();
            // Menggunakan username dari model Pelanggan untuk header
            $nama = $u->username ?? 'Sahabat';
            $jam  = now('Asia/Jakarta')->format('H');
            $waktu = $jam < 11 ? 'pagi' : ($jam < 15 ? 'siang' : ($jam < 18 ? 'sore' : 'malam'));
        @endphp
        <div class="user-name">Selamat {{ $waktu }}, {{ $nama }}!</div>
        <div class="header-icons">
            <div class="profile-menu" id="profileMenu">
                <button type="button" class="icon-wrapper profile-icon" aria-label="Profile" onclick="toggleProfileMenu(event)">
                    <img src="{{ asset('assets/profile-icon.svg') }}" alt="User profile avatar icon in circular frame" class="icon" />
                </button>
                <div class="profile-dropdown" id="profileDropdown">
                    <a href="{{ route('customer.transaksi') }}" class="profile-dropdown-item">Transaksi</a>
                    <a href="{{ route('customer.profile.edit') }}" class="profile-dropdown-item">Akun Saya</a>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="profile-dropdown-item logout" style="width: calc(100% - 16px); border: none; background: none; font-family: inherit; font-size: inherit; cursor: pointer;">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="back-button">
        <a href="{{ route('cart.index') }}" class="back-arrow">
            <img src="{{ asset('assets/back-arrow.png') }}" alt="Back arrow icon pointing left for navigation" />
            Kembali
        </a>
    </div>

    <!-- Main Container -->
    <div class="checkout-container">
        <!-- Left Column -->
        <div class="left-column">
            <div class="section-title">Ringkasan Pesanan</div>

            @php
                // Ambil data pelanggan dari Auth guard 'pelanggan'
                $pel = Auth::guard('pelanggan')->user();
                
                // Ambil alamat default (utama atau pertama)
                $defaultAddress = $alamatList->firstWhere('is_default', true) ?? $alamatList->first();
                
                // Jika ada alamat dari database, gunakan itu, jika tidak gunakan data pelanggan
                $defaultNama = $defaultAddress->nama_penerima ?? $pel->nama_pelanggan ?? 'Nama Penerima';
                $defaultPhone = $defaultAddress->telepon ?? $pel->no_hp ?? '-';
                $defaultAlamat = $defaultAddress->alamat_lengkap ?? $pel->alamat ?? '-';
            @endphp

            <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
                @csrf

                <!-- Detail Penerima & Pengiriman -->
                <div class="section-box">
                    <div class="section-header">
                        <div class="subsection-title">Detail Penerima & Pengiriman</div>
                        <button type="button" class="change-btn" onclick="openChangeModal()">Ganti Alamat</button>
                    </div>
                    <div class="divider"></div>

                    @if(session('error'))
                        <div class="alert alert-error">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Detail Penerima dalam Grid 2 Kolom -->
                    <div class="delivery-info-compact" style="margin-bottom: 16px;">
                        <div class="delivery-row">
                            <svg class="delivery-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2"/>
                            </svg>
                            <div class="delivery-text">
                                <strong>Nama:</strong>
                                <span id="displayName">{{ $defaultNama }}</span>
                            </div>
                        </div>

                        <div class="delivery-row">
                            <svg class="delivery-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-width="2"/>
                            </svg>
                            <div class="delivery-text">
                                <strong>Telepon:</strong>
                                <span id="displayPhone">{{ $defaultPhone }}</span>
                            </div>
                        </div>

                        <div class="delivery-row" style="grid-column: 1 / -1;">
                            <svg class="delivery-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2"/>
                                <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/>
                            </svg>
                            <div class="delivery-text">
                                <strong>Alamat:</strong>
                                <span id="displayAddress">{{ $defaultAlamat }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Pengiriman -->
                    <div style="padding-top: 12px; border-top: 1px solid #e5e5e5;">
                        <div class="delivery-info">
                            <div class="delivery-row">
                                <svg class="delivery-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"/>
                                    <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"/>
                                    <line x1="3" y1="10" x2="21" y2="10" stroke-width="2"/>
                                </svg>
                                <div class="delivery-text">
                                    <strong>{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</strong>
                                </div>
                            </div>

                            <div class="delivery-row">
                                <svg class="delivery-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                    <polyline points="12 6 12 12 16 14" stroke-width="2"/>
                                </svg>
                                <div class="delivery-text">
                                    Akan diproses dalam waktu maksimal 1 jam setelah pembayaran di jam operasional (07.00–21.00)
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden inputs -->
                    <input type="hidden" name="nama_penerima" id="inputName" value="{{ $defaultNama }}">
                    <input type="hidden" name="telepon_penerima" id="inputPhone" value="{{ $defaultPhone }}">
                    <input type="hidden" name="alamat_pengiriman" id="inputAddress" value="{{ $defaultAlamat }}">
                    <input type="hidden" name="tanggal_pengiriman" value="{{ now()->addDay()->format('Y-m-d') }}">
                    <input type="hidden" name="waktu_pengiriman" value="Siang">

                </div>
            </form>

            <!-- Product List -->
            <div class="product-list">
                @foreach($cart as $item)
                    <div class="product-item" data-id="{{ $item['id_barang'] ?? $item['id'] }}">
                        <!-- KIRI: gambar + nama di samping -->
                        <div class="item-image-wrapper">
                            <img src="{{ $item['gambar'] ?? 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/7d41686f-b1b9-4eb6-b575-cea8dac4923a.png' }}" 
                                 alt="Product image of {{ $item['nama'] }}" 
                                 class="product-image">
                            <div class="item-name">{{ $item['nama'] }}</div>
                        </div>

                        <!-- TENGAH: kontrol qty saja -->
                        <div class="item-controls">
                            <div class="item-quantity">
                                <button type="button" 
                                        class="btn-circle" 
                                        onclick="updateQuantity('{{ $item['id_barang'] ?? $item['id'] }}', -1)"
                                        {{ $item['qty'] <= 1 ? 'disabled' : '' }}>
                                    <img src="{{ $item['qty'] > 1 ? asset('assets/btn-minus-active.svg') : asset('assets/btn-minus-disabled.svg') }}" 
                                         alt="Minus button" />
                                </button>
                                <span class="qty-value" data-qty="{{ $item['qty'] }}">{{ $item['qty'] }}</span>
                                <button type="button" class="btn-circle" onclick="updateQuantity('{{ $item['id_barang'] ?? $item['id'] }}', 1)">
                                    <img src="{{ asset('assets/btn-plus.svg') }}" alt="Plus button" />
                                </button>
                            </div>
                        </div>

                        <!-- KANAN: trash di atas, harga di bawah -->
                        <div class="item-actions">
                            <button type="button" class="remove-btn" onclick="removeItem('{{ $item['id_barang'] ?? $item['id'] }}')">
                                <img src="{{ asset('assets/trash-icon.png') }}" alt="Red trash bin icon for removing single item from shopping cart" />
                            </button>

                            <div class="product-price" data-price="{{ $item['harga'] }}">
                                Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right Column - Summary -->
        <div class="summary-sidebar">
            <div class="summary-card">
                <h3 class="summary-title">Ringkasan Pesanan</h3>

                <div class="summary-row">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value" id="summarySubtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">Diskon</span>
                    <span class="summary-value">Rp {{ number_format($diskon, 0, ',', '.') }}</span>
                </div>

                <div class="summary-divider"></div>

                <div class="total-row">
                    <span class="total-label">Total Belanja</span>
                    <span class="total-value" id="summaryTotal">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <button type="submit" form="checkoutForm" class="checkout-btn">
                    Bayar Sekarang
                </button>
            </div>
        </div>
    </div>

    <!-- Change Address Modal -->
    <div class="modal" id="changeModal">
       <div class="modal-content">
  <div class="modal-header">
    <h3 class="modal-title">Mau di kirim kemana?</h3>
    <button type="button" class="close-modal" onclick="closeChangeModal()">&times;</button>
  </div>

  <!-- Benefit / info bar (opsional) -->
  <div class="delivery-benefit">
    <img src="{{ asset('assets/delivery.png') }}" alt="Delivery Icon" />
    <div>
      <div class="benefit-sub">Pastikan Anda memilih alamat yang tepat proses pengiriman berjalan lebih lancar</div>
    </div>
  </div>

  <!-- DAFTAR ALAMAT -->
  <div class="address-list" id="addressList">
@php
    // Ambil pelanggan login dari guard 'pelanggan'
    $pel = Auth::guard('pelanggan')->user();

    // Ambil alamat dari database - $alamatList selalu dikirim dari controller
    // Gunakan collection kosong jika tidak ada
    $addresses = $alamatList ?? collect([]);
    
    // Pastikan $addresses adalah collection yang berisi semua alamat
    // Debug: uncomment baris di bawah untuk melihat jumlah alamat
    // {{-- Total alamat: {{ $addresses->count() }} --}}
    
    // Yang terpilih default: alamat utama atau alamat pertama
    $selectedAddressId = $addresses->firstWhere('is_default', true)?->id ?? $addresses->first()?->id ?? null;
@endphp

    @forelse($addresses as $addr)
      @php
        $addrId = $addr->id ?? null;
        $isSelected = $selectedAddressId == $addrId;
      @endphp
      <div
        class="addr-card {{ $isSelected ? 'selected' : '' }}"
        data-id="{{ $addrId }}"
        data-nama="{{ $addr->nama_penerima }}"
        data-phone="{{ $addr->telepon }}"
        data-alamat="{{ $addr->alamat_lengkap }}"
      >
        <div class="addr-card-header">
          <div class="addr-label">{{ $addr->label ?? 'Alamat' }}</div>
          <div class="addr-badges">
            @if($addr->is_default ?? false)
              <span class="badge badge-primary">Alamat Utama</span>
            @endif
            @if($isSelected)
              <span class="badge badge-selected">Alamat Terpilih</span>
            @endif
          </div>
        </div>

        <div class="addr-name">{{ strtoupper($addr->nama_penerima) }}</div>
        <div class="addr-phone">{{ $addr->telepon }}</div>
        @if(!empty($addr->catatan))
          <div class="addr-note">{{ $addr->catatan }}</div>
        @endif
        <div class="addr-full">{{ $addr->alamat_lengkap }}</div>

        @if(!empty($addr->lat) && !empty($addr->lng))
          <a class="addr-pin" target="_blank" href="https://maps.google.com/?q={{ $addr->lat }},{{ $addr->lng }}">Lokasi Sudah Ditandai</a>
        @endif

        <div class="addr-actions">
          <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; width: 100%;">
            <label class="addr-radio" style="flex: 1;">
              <input type="radio" name="selected_address_id"
                     value="{{ $addrId }}"
                     {{ $isSelected ? 'checked' : '' }}
                     onclick="chooseAddress('{{ $addrId }}')">
              Pilih alamat ini
            </label>
            <div style="display: flex; gap: 8px; align-items: center;">
              @if(!($addr->is_default ?? false))
                <button type="button" 
                        class="set-default-btn" 
                        onclick="setAsDefault('{{ $addrId }}')"
                        title="Jadikan alamat utama">
                  Set Utama
                </button>
              @endif
              @php
                $totalAddresses = $addresses->count();
              @endphp
              <button type="button" 
                      class="delete-addr-btn" 
                      onclick="deleteAddress('{{ $addrId }}', {{ $totalAddresses }})"
                      title="Hapus alamat"
                      {{ $totalAddresses <= 1 ? 'disabled' : '' }}>
                <img src="{{ asset('assets/trash-icon.png') }}" alt="Hapus alamat" style="width: 18px; height: 18px;" />
              </button>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="empty-address">Kamu belum punya alamat tersimpan.</div>
    @endforelse
  </div>

  <!-- Tambah alamat -->
  <div class="add-address-section">
    <div class="add-title">Mau pakai alamat lain?</div>
    <button type="button" class="add-btn" onclick="toggleAddForm()">Tambah Alamat</button>

    <form id="addAddressForm" class="add-form" style="display:none" onsubmit="return createAddress(event)">
      <div class="form-row">
        <label for="newLabel">Label Alamat</label>
        <input type="text" id="newLabel" name="label" placeholder="Rumah / Kos / Kantor" autocomplete="off">
      </div>
      <div class="form-row">
        <label for="newNama">Nama Penerima <span style="color: #ff5722;">*</span></label>
        <input type="text" id="newNama" name="nama" required autocomplete="name">
      </div>
      <div class="form-row">
        <label for="newPhone">No. Telepon <span style="color: #ff5722;">*</span></label>
        <input type="tel" id="newPhone" name="phone" required autocomplete="tel" placeholder="08xxxxxxxxxx">
      </div>
      <div class="form-row">
        <label for="newAlamat">Alamat Lengkap <span style="color: #ff5722;">*</span></label>
        <textarea id="newAlamat" name="alamat" rows="4" required autocomplete="street-address" placeholder="Masukkan alamat lengkap termasuk nama jalan, RT/RW, kelurahan, kecamatan, kota, dan kode pos"></textarea>
      </div>
      <div class="form-row inline">
        <input type="checkbox" id="newDefault" name="is_default">
        <label for="newDefault">Jadikan sebagai alamat utama</label>
      </div>
      <div style="margin-top: 8px; padding-top: 16px; border-top: 1px solid #e5e5e5;">
        <button type="submit" class="save-btn">Simpan & Gunakan</button>
      </div>
    </form>
  </div>
</div>
</div>

    <!-- Profile Overlay -->
    <div class="profile-overlay" id="profileOverlay" onclick="closeProfileMenu()"></div>

    <!-- Footer -->
    <div class="footer" id="mainFooter">
        <h2 class="footer-title">Toko Kelontong Bu Untung, Belanja menjadi mudah</h2>
        <p class="footer-text">
            Toko Kelontong Bu Untung adalah usaha ritel di Kebumen yang menyediakan berbagai kebutuhan pokok seperti beras, gula, minyak goreng, makanan ringan, serta barang rumah tangga lainnya. Toko ini melayani warga sekitar dan menjadi tempat belanja yang praktis serta terjangkau.
        </p>

        <h3 class="footer-subtitle">Cara Belanja di Toko Kelontong Bu Untung</h3>
        <p class="footer-text">
            Belanja kebutuhan harian kini jadi lebih praktis dan hemat waktu. Tanpa perlu keluar rumah atau menghadapi kemacetan, kamu bisa memenuhi semua kebutuhan hanya melalui website Toko Kelontong Bu Untung.
        </p>
        <p class="footer-text">
            Cukup buka website ini, pilih produk yang kamu butuhkan, lalu selesaikan pembayaran dengan mudah menggunakan QRIS. Tidak perlu install aplikasi, tidak ada biaya tambahan—semua pesanan dikirim langsung tanpa ribet! Belanja jadi lebih santai, cepat, dan menyenangkan, langsung dari HP atau laptop, kapan pun kamu butuh. Toko Kelontong Bu Untung siap melayani kamu dari berbagai penjuru Indonesia, dengan produk lengkap dan harga terjangkau.
        </p>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Toast Notification Function
        function showToastNotification(message) {
            // Hapus toast yang sudah ada jika ada
            const existingToast = document.querySelector('.toast-notification');
            if (existingToast) {
                existingToast.remove();
            }

            // Buat elemen toast baru
            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            
            // SVG Checkmark Icon
            const checkmarkIcon = `
                <svg class="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            `;
            
            toast.innerHTML = checkmarkIcon + `<span>${message}</span>`;
            
            // Tambahkan ke body
            document.body.appendChild(toast);
            
            // Trigger animasi masuk
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);
            
            // Hapus setelah 3 detik
            setTimeout(() => {
                toast.classList.remove('show');
                toast.classList.add('hide');
                setTimeout(() => {
                    toast.remove();
                }, 400);
            }, 3000);
        }

        // Modal Functions
        function openChangeModal() {
            const modal = document.getElementById('changeModal');
            modal.style.display = 'flex';
            // Trigger reflow untuk memastikan animasi berjalan
            modal.offsetHeight;
            modal.classList.add('active');
            // Sembunyikan footer saat modal dibuka
            const footer = document.getElementById('mainFooter');
            if (footer) {
                footer.style.display = 'none';
            }
        }

        function closeChangeModal() {
            const modal = document.getElementById('changeModal');
            
            // Tambahkan class closing untuk animasi fade out
            modal.classList.add('closing');
            modal.classList.remove('active');
            
            // Setelah animasi selesai, hapus class closing dan sembunyikan modal
            setTimeout(() => {
                modal.classList.remove('closing');
                modal.style.display = 'none';
                
                // Tampilkan kembali footer saat modal ditutup
                const footer = document.getElementById('mainFooter');
                if (footer) {
                    footer.style.display = 'block';
                }
            }, 400);
        }

        function saveChanges() {
            const name = document.getElementById('modalName')?.value;
            const phone = document.getElementById('modalPhone')?.value;
            const address = document.getElementById('modalAddress')?.value;

            if (!name || !phone || !address) {
                alert('Semua field harus diisi!');
                return;
            }

            // Update display dengan format baru
            document.getElementById('displayName').textContent = name;
            document.getElementById('displayPhone').textContent = phone;
            document.getElementById('displayAddress').textContent = address;

            // Update hidden inputs
            document.getElementById('inputName').value = name;
            document.getElementById('inputPhone').value = phone;
            document.getElementById('inputAddress').value = address;

            closeChangeModal();
        }

        // Close modal on outside click
        document.getElementById('changeModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeChangeModal();
            }
        });

        // Cart Functions
        function updateQuantity(id, change) {
            const item = document.querySelector(`[data-id="${id}"]`);
            const qtyEl = item.querySelector('.qty-value');
            const currentQty = parseInt(qtyEl.dataset.qty);
            const newQty = currentQty + change;

            if (newQty < 1) {
                if (confirm('Hapus produk dari keranjang?')) {
                    removeItem(id);
                }
                return;
            }

            // Update minus button icon and disabled state
            const minusBtn = item.querySelector('.item-quantity .btn-circle:first-child');
            const minusBtnImg = minusBtn ? minusBtn.querySelector('img') : null;
            if (minusBtn && minusBtnImg) {
                if (newQty === 1) {
                    minusBtn.disabled = true;
                    minusBtnImg.src = '{{ asset('assets/btn-minus-disabled.svg') }}';
                } else {
                    minusBtn.disabled = false;
                    minusBtnImg.src = '{{ asset('assets/btn-minus-active.svg') }}';
                }
            }

            fetch('/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    id_barang: id,
                    jumlah_pesanan: newQty
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    qtyEl.textContent = newQty;
                    qtyEl.dataset.qty = newQty;
                    
                    const price = parseInt(item.querySelector('.product-price').dataset.price);
                    item.querySelector('.product-price').textContent = 'Rp ' + (price * newQty).toLocaleString('id-ID');
                    
                    updateSummary();
                }
            });
        }

        function removeItem(id) {
            if (!confirm('Hapus produk dari keranjang?')) return;

            fetch(`/cart/remove/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const item = document.querySelector(`[data-id="${id}"]`);
                    item.style.animation = 'fadeOut .3s ease-out';
                    setTimeout(() => {
                        item.remove();
                        updateSummary();
                        
                        if (document.querySelectorAll('.product-item').length === 0) {
                            window.location.href = '/cart';
                        }
                    }, 300);
                }
            });
        }

        function updateSummary() {
            let subtotal = 0;
            document.querySelectorAll('.product-item').forEach(item => {
                const qty = parseInt(item.querySelector('.qty-value').dataset.qty);
                const price = parseInt(item.querySelector('.product-price').dataset.price);
                subtotal += qty * price;
            });

            document.getElementById('summarySubtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('summaryTotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        }
  function chooseAddress(id) {
    // tandai kartu
    const chosen = document.querySelector(`.addr-card[data-id="${id}"]`);
    
    // Tambahkan animasi pulse pada kartu yang dipilih
    chosen.classList.add('selecting');
    
    document.querySelectorAll('.addr-card').forEach(card => {
      card.classList.toggle('selected', card.dataset.id === String(id));
      const badgeSel = card.querySelector('.badge-selected');
      if (badgeSel) badgeSel.remove();
      if (card.classList.contains('selected')) {
        const slot = document.createElement('span');
        slot.className = 'badge badge-selected';
        slot.textContent = 'Alamat Terpilih';
        card.querySelector('.addr-badges').appendChild(slot);
      }
    });

    // ambil detail & isi ke tampilan + hidden inputs
    const nama = chosen.dataset.nama;
    const phone = chosen.dataset.phone;
    const alamat = chosen.dataset.alamat;

    // Tambahkan animasi flash pada baris detail yang diupdate
    const displayNameEl = document.getElementById('displayName');
    const displayPhoneEl = document.getElementById('displayPhone');
    const displayAddressEl = document.getElementById('displayAddress');
    
    const nameRow = displayNameEl ? displayNameEl.closest('.delivery-row') : null;
    const phoneRow = displayPhoneEl ? displayPhoneEl.closest('.delivery-row') : null;
    const addressRow = displayAddressEl ? displayAddressEl.closest('.delivery-row') : null;
    
    [nameRow, phoneRow, addressRow].forEach(row => {
      if (row) {
        row.classList.add('updating');
        setTimeout(() => {
          row.classList.remove('updating');
        }, 500);
      }
    });

    // Update display dengan format baru (dengan animasi fade)
    const displayName = document.getElementById('displayName');
    const displayPhone = document.getElementById('displayPhone');
    const displayAddress = document.getElementById('displayAddress');
    
    if (displayName && displayPhone && displayAddress) {
      // Fade out
      [displayName, displayPhone, displayAddress].forEach(el => {
        el.style.transition = 'opacity 0.2s ease';
        el.style.opacity = '0.5';
      });
      
      // Update nilai setelah fade out
      setTimeout(() => {
        displayName.textContent = nama;
        displayPhone.textContent = phone;
        displayAddress.textContent = alamat;
        
        // Fade in
        [displayName, displayPhone, displayAddress].forEach(el => {
          el.style.opacity = '1';
        });
      }, 200);
    } else {
      // Fallback jika elemen tidak ditemukan
      if (displayName) displayName.textContent = nama;
      if (displayPhone) displayPhone.textContent = phone;
      if (displayAddress) displayAddress.textContent = alamat;
    }

    // Update hidden inputs
    document.getElementById('inputName').value = nama;
    document.getElementById('inputPhone').value = phone;
    document.getElementById('inputAddress').value = alamat;

    // Hapus class animasi setelah selesai
    setTimeout(() => {
      chosen.classList.remove('selecting');
    }, 600);

    // Tampilkan notifikasi sukses setelah animasi kartu
    setTimeout(() => {
      showToastNotification('Alamat berhasil diganti!');
    }, 400);

    // Delay 3 detik sebelum menutup modal
    setTimeout(() => {
      closeChangeModal();
    }, 3000);
  }

  function toggleAddForm(){
    const f = document.getElementById('addAddressForm');
    f.style.display = f.style.display === 'none' ? 'block' : 'none';
  }

  function createAddress(event) {
    event.preventDefault();
    
    const label = document.getElementById('newLabel').value || 'Alamat';
    const nama = document.getElementById('newNama').value;
    const phone = document.getElementById('newPhone').value;
    const alamat = document.getElementById('newAlamat').value;
    const isDefault = document.getElementById('newDefault').checked;
    
    if (!nama || !phone || !alamat) {
      alert('Semua field harus diisi!');
      return false;
    }
    
    // Kirim data ke API untuk menyimpan ke database
    fetch('{{ route("alamat.store") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify({
        label: label,
        nama_penerima: nama,
        telepon: phone,
        alamat_lengkap: alamat,
        is_default: isDefault
      })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const addr = data.data;
        
        // Hapus badge "Alamat Terpilih" dari semua kartu yang ada
        document.querySelectorAll('.addr-card').forEach(card => {
          card.classList.remove('selected');
          const badgeSel = card.querySelector('.badge-selected');
          if (badgeSel) badgeSel.remove();
          const radio = card.querySelector('input[type="radio"]');
          if (radio) radio.checked = false;
        });
        
        // Jika isDefault, hapus badge "Alamat Utama" dari kartu lain
        if (isDefault) {
          document.querySelectorAll('.badge-primary').forEach(badge => {
            badge.remove();
          });
        }
        
        // Buat elemen kartu alamat baru
        const addressList = document.getElementById('addressList');
        const newCard = document.createElement('div');
        newCard.className = 'addr-card selected new-address-card';
        newCard.setAttribute('data-id', addr.id);
        newCard.setAttribute('data-nama', addr.nama_penerima);
        newCard.setAttribute('data-phone', addr.telepon);
        newCard.setAttribute('data-alamat', addr.alamat_lengkap);
        
        // Hapus class animasi setelah animasi selesai
        setTimeout(() => {
          newCard.classList.remove('new-address-card');
        }, 400);
        
        // Hitung total alamat setelah penambahan
        const totalAfterAdd = document.querySelectorAll('.addr-card').length + 1;
        
        newCard.innerHTML = `
          <div class="addr-card-header">
            <div class="addr-label">${addr.label || 'Alamat'}</div>
            <div class="addr-badges">
              ${addr.is_default ? '<span class="badge badge-primary">Alamat Utama</span>' : ''}
              <span class="badge badge-selected">Alamat Terpilih</span>
            </div>
          </div>
          <div class="addr-name">${addr.nama_penerima.toUpperCase()}</div>
          <div class="addr-phone">${addr.telepon}</div>
          ${addr.catatan ? `<div class="addr-note">${addr.catatan}</div>` : ''}
          <div class="addr-full">${addr.alamat_lengkap}</div>
          ${addr.lat && addr.lng ? `<a class="addr-pin" target="_blank" href="https://maps.google.com/?q=${addr.lat},${addr.lng}">Lokasi Sudah Ditandai</a>` : ''}
          <div class="addr-actions">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; width: 100%;">
              <label class="addr-radio" style="flex: 1;">
                <input type="radio" name="selected_address_id" value="${addr.id}" checked onclick="chooseAddress('${addr.id}')">
                Pilih alamat ini
              </label>
              <div style="display: flex; gap: 8px; align-items: center;">
                ${!addr.is_default ? `<button type="button" class="set-default-btn" onclick="setAsDefault('${addr.id}')" title="Jadikan alamat utama">Set Utama</button>` : ''}
                <button type="button" class="delete-addr-btn" onclick="deleteAddress('${addr.id}', ${totalAfterAdd})" title="Hapus alamat" ${totalAfterAdd <= 1 ? 'disabled' : ''}>
                  <img src="{{ asset('assets/trash-icon.png') }}" alt="Hapus alamat" style="width: 18px; height: 18px;" />
                </button>
              </div>
            </div>
          </div>
        `;
        
        // Tambahkan kartu baru ke dalam daftar alamat
        addressList.appendChild(newCard);
        
        // Update total alamat setelah penambahan
        const finalTotal = document.querySelectorAll('.addr-card').length;
        
        // Update semua tombol delete dengan total yang baru
        document.querySelectorAll('.delete-addr-btn').forEach(btn => {
          const card = btn.closest('.addr-card');
          if (card) {
            const addrId = card.getAttribute('data-id');
            btn.disabled = finalTotal <= 1;
            btn.setAttribute('onclick', `deleteAddress('${addrId}', ${finalTotal})`);
          }
        });
        
        // Update display dan hidden inputs dengan alamat baru
        document.getElementById('displayName').textContent = nama;
        document.getElementById('displayPhone').textContent = phone;
        document.getElementById('displayAddress').textContent = alamat;
        
        document.getElementById('inputName').value = nama;
        document.getElementById('inputPhone').value = phone;
        document.getElementById('inputAddress').value = alamat;
        
        // Reset form
        document.getElementById('addAddressForm').reset();
        document.getElementById('addAddressForm').style.display = 'none';
        
        // Tampilkan notifikasi sukses
        showToastNotification('Alamat berhasil ditambahkan!');
      } else {
        alert('Gagal menyimpan alamat: ' + (data.message || 'Terjadi kesalahan'));
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Gagal menyimpan alamat. Silakan coba lagi.');
    });
    
    return false;
  }

  function deleteAddress(id, totalAddresses) {
    // Validasi: minimal harus ada 1 alamat
    if (totalAddresses <= 1) {
      alert('Anda harus memiliki minimal 1 alamat. Tidak dapat menghapus alamat terakhir.');
      return;
    }

    // Konfirmasi penghapusan
    if (!confirm('Apakah Anda yakin ingin menghapus alamat ini?')) {
      return;
    }

    // Hapus alamat via API
    fetch(`/alamat/${id}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        // Hapus kartu alamat dari DOM dengan animasi
        const card = document.querySelector(`.addr-card[data-id="${id}"]`);
        if (card) {
          card.style.animation = 'fadeOut .3s ease-out';
          setTimeout(() => {
            card.remove();
            
            // Update total alamat yang tersisa
            const remainingAddresses = document.querySelectorAll('.addr-card').length;
            
            // Update disabled state pada semua tombol delete
            document.querySelectorAll('.delete-addr-btn').forEach(btn => {
              const card = btn.closest('.addr-card');
              if (card) {
                const totalAddr = document.querySelectorAll('.addr-card').length;
                btn.disabled = totalAddr <= 1;
                // Update onclick dengan total yang baru
                const addrId = card.getAttribute('data-id');
                btn.setAttribute('onclick', `deleteAddress('${addrId}', ${totalAddr})`);
              }
            });
            
            // Jika alamat yang dihapus adalah yang terpilih, pilih alamat pertama yang tersisa
            const selectedCard = document.querySelector('.addr-card.selected');
            if (!selectedCard && remainingAddresses > 0) {
              const firstCard = document.querySelector('.addr-card');
              if (firstCard) {
                const firstId = firstCard.getAttribute('data-id');
                chooseAddress(firstId);
              }
            }
            
            // Tampilkan notifikasi sukses
            showToastNotification('Alamat berhasil dihapus!');
            
            // Delay 3 detik sebelum menutup modal
            setTimeout(() => {
              closeChangeModal();
            }, 3000);
          }, 300);
        }
      } else {
        alert('Gagal menghapus alamat: ' + (data.message || 'Terjadi kesalahan'));
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Gagal menghapus alamat. Silakan coba lagi.');
    });
  }

  function setAsDefault(id) {
    // Ambil kartu alamat
    const card = document.querySelector(`.addr-card[data-id="${id}"]`);
    if (!card) return;

    // Update alamat menjadi default via API
    fetch(`/alamat/${id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify({
        is_default: true
      })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        // Hapus badge "Alamat Utama" dari semua kartu
        document.querySelectorAll('.badge-primary').forEach(badge => {
          badge.remove();
        });
        
        // Tampilkan kembali semua tombol "Set Utama" yang tersembunyi
        document.querySelectorAll('.set-default-btn').forEach(btn => {
          btn.style.display = 'block';
        });
        
        // Tambahkan badge "Alamat Utama" ke kartu yang dipilih
        const badgesContainer = card.querySelector('.addr-badges');
        if (badgesContainer) {
          const badge = document.createElement('span');
          badge.className = 'badge badge-primary';
          badge.textContent = 'Alamat Utama';
          badgesContainer.insertBefore(badge, badgesContainer.firstChild);
        }
        
        // Sembunyikan tombol "Set Utama" dari kartu ini
        const setDefaultBtn = card.querySelector('.set-default-btn');
        if (setDefaultBtn) {
          setDefaultBtn.style.display = 'none';
        }
        
        // Tampilkan notifikasi sukses
        showToastNotification('Alamat berhasil dijadikan alamat utama!');
        
        // Delay 3 detik sebelum menutup modal
        setTimeout(() => {
          closeChangeModal();
        }, 3000);
      } else {
        alert('Gagal mengubah alamat utama: ' + (data.message || 'Terjadi kesalahan'));
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Gagal mengubah alamat utama. Silakan coba lagi.');
    });
  }

        // Profile Menu Functions
        function toggleProfileMenu(event) {
            if (event) {
                event.stopPropagation();
                event.preventDefault();
            }
            const dropdown = document.getElementById('profileDropdown');
            const overlay = document.getElementById('profileOverlay');
            const profileMenu = document.getElementById('profileMenu');
            
            const isActive = dropdown.classList.contains('active');
            if (isActive) {
                dropdown.classList.remove('active');
                overlay.classList.remove('active');
                if (profileMenu) {
                    profileMenu.classList.remove('active');
                }
            } else {
                dropdown.classList.add('active');
                overlay.classList.add('active');
                if (profileMenu) {
                    profileMenu.classList.add('active');
                }
            }
        }

        function closeProfileMenu() {
            const dropdown = document.getElementById('profileDropdown');
            const overlay = document.getElementById('profileOverlay');
            const profileMenu = document.getElementById('profileMenu');
            
            if (dropdown) dropdown.classList.remove('active');
            if (overlay) overlay.classList.remove('active');
            if (profileMenu) profileMenu.classList.remove('active');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const profileMenu = document.getElementById('profileMenu');
            const dropdown = document.getElementById('profileDropdown');
            const overlay = document.getElementById('profileOverlay');
            
            // Jika dropdown tidak aktif, tidak perlu melakukan apa-apa
            if (!dropdown || !dropdown.classList.contains('active')) {
                return;
            }
            
            // Jika klik pada overlay, tutup dropdown
            if (event.target === overlay) {
                closeProfileMenu();
                return;
            }
            
            // Jika klik di luar profile menu, tutup dropdown
            if (profileMenu && !profileMenu.contains(event.target)) {
                closeProfileMenu();
            }
        });
    </script>
</body>
</html>