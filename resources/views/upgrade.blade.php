@extends('layouts.user')

@section('title', 'Checkout — Daleel AI')

@section('styles')
<style>
    :root {
        --checkout-bg: #f8f9fb;
        --checkout-white: #ffffff;
        --checkout-border: #e2e8f0;
        --checkout-text: #0f172a;
        --checkout-text-secondary: #475569;
        --checkout-primary: #4f46e5;
        --checkout-primary-hover: #4338ca;
        --checkout-accent: #6366f1;
        --checkout-accent-light: #eef2ff;
        --checkout-success: #10b981;
        --checkout-success-light: #ecfdf5;
        --checkout-warning: #f59e0b;
        --checkout-warning-light: #fffbeb;
        --checkout-radius: 12px;
        --checkout-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.05), 0 2px 4px -1px rgba(99, 102, 241, 0.03);
        --checkout-shadow-lg: 0 10px 15px -3px rgba(99, 102, 241, 0.04), 0 4px 6px -2px rgba(99, 102, 241, 0.02);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background-color: var(--checkout-bg);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .checkout-container {
        max-width: 1040px;
        margin: 48px auto;
        padding: 0 24px;
    }

    .checkout-wrapper {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 0;
        background: var(--checkout-white);
        border-radius: 16px;
        box-shadow: var(--checkout-shadow-lg);
        overflow: hidden;
        border: 1px solid var(--checkout-border);
    }

    @media (max-width: 900px) {
        .checkout-wrapper {
            grid-template-columns: 1fr;
        }
        .checkout-container {
            margin: 24px auto;
            padding: 0 16px;
        }
    }

    /* Left Section */
    .checkout-left {
        padding: 48px 48px 40px;
    }

    @media (max-width: 900px) {
        .checkout-left {
            padding: 32px 24px 28px;
        }
    }

    .checkout-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--checkout-text-secondary);
        margin-bottom: 32px;
        font-weight: 500;
    }

    .checkout-breadcrumb span {
        color: var(--checkout-primary);
        font-weight: 600;
    }

    .checkout-breadcrumb svg {
        width: 14px;
        height: 14px;
    }

    .section {
        margin-bottom: 36px;
    }

    .section:last-child {
        margin-bottom: 0;
    }

    .section-header {
        margin-bottom: 16px;
    }

    .section-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--checkout-text-secondary);
        margin-bottom: 6px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--checkout-text);
        letter-spacing: -0.01em;
    }

    /* Country Selector */
    .form-group {
        position: relative;
    }

    .form-select {
        width: 100%;
        height: 48px;
        padding: 0 44px 0 16px;
        font-size: 14px;
        font-weight: 500;
        color: var(--checkout-text);
        background-color: var(--checkout-white);
        border: 1.5px solid var(--checkout-border);
        border-radius: 10px;
        appearance: none;
        cursor: pointer;
        transition: all 0.15s ease;
        font-family: inherit;
    }

    .form-select:hover {
        border-color: #d1d5db;
    }

    .form-select:focus {
        outline: none;
        border-color: var(--checkout-primary);
        box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.06);
    }

    .form-select-icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: var(--checkout-text-secondary);
        width: 16px;
        height: 16px;
    }

    .form-hint {
        margin-top: 8px;
        font-size: 13px;
        color: var(--checkout-text-secondary);
        line-height: 1.5;
        display: flex;
        align-items: flex-start;
        gap: 6px;
    }

    .form-hint svg {
        width: 14px;
        height: 14px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    /* Payment Methods */
    .payment-options {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .payment-option {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border: 1.5px solid var(--checkout-border);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.15s ease;
        background: var(--checkout-white);
    }

    .payment-option:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }

    .payment-option.selected {
        border-color: var(--checkout-primary);
        background: var(--checkout-accent-light);
    }

    .payment-option-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .payment-radio {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid #d1d5db;
        position: relative;
        flex-shrink: 0;
        transition: all 0.15s ease;
    }

    .payment-option.selected .payment-radio {
        border-color: var(--checkout-primary);
    }

    .payment-radio::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0);
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--checkout-primary);
        transition: transform 0.15s ease;
    }

    .payment-option.selected .payment-radio::after {
        transform: translate(-50%, -50%) scale(1);
    }

    .payment-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--checkout-text);
    }

    .payment-badges {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .payment-badge {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.03em;
        color: var(--checkout-text-secondary);
        padding: 3px 8px;
        border: 1px solid var(--checkout-border);
        border-radius: 4px;
        background: var(--checkout-white);
    }

    .payment-option.selected .payment-badge {
        background: #f5f5f5;
    }

    /* Order Items */
    .order-items {
        border: 1.5px solid var(--checkout-border);
        border-radius: 10px;
        overflow: hidden;
    }

    .order-item {
        display: flex;
        align-items: center;
        padding: 20px;
        gap: 16px;
        border-bottom: 1px solid var(--checkout-border);
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .order-item-thumb {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #818cf8, #4f46e5);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
        color: #ffffff;
    }

    .order-item-info {
        flex: 1;
        min-width: 0;
    }

    .order-item-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--checkout-text);
        margin-bottom: 4px;
    }

    .order-item-meta {
        font-size: 12px;
        color: var(--checkout-text-secondary);
    }

    .order-item-price {
        text-align: right;
        flex-shrink: 0;
    }

    .order-item-price-current {
        font-size: 16px;
        font-weight: 700;
        color: var(--checkout-text);
    }

    .order-item-price-original {
        font-size: 13px;
        color: #9ca3af;
        text-decoration: line-through;
    }

    .feature-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 24px;
        padding: 16px 20px;
        background: #fafafa;
        list-style: none;
    }

    .feature-list li {
        font-size: 12px;
        color: var(--checkout-text-secondary);
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        position: relative;
        padding-left: 18px;
    }

    .feature-list li::before {
        display: none;
    }

    .feature-list li::after {
        content: '✓';
        position: absolute;
        left: 0;
        color: var(--checkout-success);
        font-weight: 800;
        font-size: 13px;
    }

    /* Right Section */
    .checkout-right {
        background: #fafafa;
        border-left: 1px solid var(--checkout-border);
        padding: 40px 32px;
        display: flex;
        flex-direction: column;
    }

    @media (max-width: 900px) {
        .checkout-right {
            border-left: none;
            border-top: 1px solid var(--checkout-border);
            padding: 32px 24px;
        }
    }

    .summary-header {
        font-size: 16px;
        font-weight: 700;
        color: var(--checkout-text);
        margin-bottom: 20px;
        letter-spacing: -0.01em;
    }

    .summary-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .summary-table tr {
        border-bottom: 1px solid #f3f4f6;
    }

    .summary-table tr:last-child {
        border-bottom: none;
    }

    .summary-table td {
        padding: 10px 0;
        font-size: 14px;
        color: var(--checkout-text-secondary);
    }

    .summary-table td:last-child {
        text-align: right;
        font-weight: 500;
        color: var(--checkout-text);
    }

    .summary-table .summary-total td {
        font-size: 17px;
        font-weight: 700;
        color: var(--checkout-text);
        padding-top: 16px;
        border-top: 2px solid var(--checkout-border);
    }

    .savings-tag {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        color: #047857;
        background: #d1fae5;
        padding: 4px 10px;
        border-radius: 9999px;
        margin-left: 8px;
        border: 1px solid #a7f3d0;
    }

    .secure-notice {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px;
        font-size: 12px;
        color: var(--checkout-text-secondary);
        font-weight: 500;
        margin-bottom: 16px;
    }

    .secure-notice svg {
        width: 14px;
        height: 14px;
    }

    .terms {
        font-size: 12px;
        color: var(--checkout-text-secondary);
        text-align: center;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .terms a {
        color: var(--checkout-text);
        text-decoration: underline;
        text-underline-offset: 2px;
        font-weight: 500;
    }

    .terms a:hover {
        color: #000;
    }

    .btn-pay {
        width: 100%;
        height: 52px;
        background: var(--checkout-primary);
        color: #ffffff;
        font-size: 15px;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.15s ease;
        letter-spacing: -0.01em;
        margin-bottom: 24px;
        font-family: inherit;
    }

    .btn-pay:hover {
        background: var(--checkout-primary-hover);
    }

    .btn-pay:disabled {
        background: #9ca3af;
        cursor: not-allowed;
    }

    .guarantee-box {
        margin-top: auto;
        padding-top: 24px;
        border-top: 1px solid var(--checkout-border);
        text-align: center;
    }

    .guarantee-icon {
        width: 36px;
        height: 36px;
        margin: 0 auto 10px;
        color: var(--checkout-text-secondary);
    }

    .guarantee-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--checkout-text);
        margin-bottom: 4px;
    }

    .guarantee-text {
        font-size: 12px;
        color: var(--checkout-text-secondary);
        line-height: 1.5;
    }

    /* Loading */
    .spinner {
        width: 18px;
        height: 18px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .checkout-success-message {
        display: none;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        margin-bottom: 16px;
        border-radius: var(--checkout-radius);
        background: var(--checkout-success-light);
        border: 1px solid rgba(16, 185, 129, 0.22);
        color: #065f46;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.45;
    }

    .checkout-success-message.show {
        display: flex;
    }

    .checkout-success-message svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        color: var(--checkout-success);
    }
</style>
@endsection

@section('content')
<div class="checkout-container">
    <div class="checkout-wrapper">
        
        <!-- Left Column -->
        <div class="checkout-left">
            <div class="checkout-breadcrumb">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
                <span>/</span> Plans <span>/</span> <span>Checkout</span>
            </div>

            <!-- Billing Address -->
            <div class="section">
                <div class="section-header">
                    <div class="section-label">Billing Address</div>
                    <div class="section-title">Select your country</div>
                </div>
                <div class="form-group">
                    <select name="billing_country" class="form-select">
                        <option value="">Select a country</option>
                        <option value="AF">Afghanistan</option>
                        <option value="AL">Albania</option>
                        <option value="DZ">Algeria</option>
                        <option value="AR">Argentina</option>
                        <option value="AM">Armenia</option>
                        <option value="AU">Australia</option>
                        <option value="AT">Austria</option>
                        <option value="AZ">Azerbaijan</option>
                        <option value="BH">Bahrain</option>
                        <option value="BD">Bangladesh</option>
                        <option value="BY">Belarus</option>
                        <option value="BE">Belgium</option>
                        <option value="BT">Bhutan</option>
                        <option value="BO">Bolivia</option>
                        <option value="BA">Bosnia and Herzegovina</option>
                        <option value="BR">Brazil</option>
                        <option value="BN">Brunei</option>
                        <option value="BG">Bulgaria</option>
                        <option value="KH">Cambodia</option>
                        <option value="CM">Cameroon</option>
                        <option value="CA">Canada</option>
                        <option value="CL">Chile</option>
                        <option value="CN">China</option>
                        <option value="CO">Colombia</option>
                        <option value="CR">Costa Rica</option>
                        <option value="HR">Croatia</option>
                        <option value="CU">Cuba</option>
                        <option value="CY">Cyprus</option>
                        <option value="CZ">Czech Republic</option>
                        <option value="DK">Denmark</option>
                        <option value="DO">Dominican Republic</option>
                        <option value="EC">Ecuador</option>
                        <option value="EG">Egypt</option>
                        <option value="SV">El Salvador</option>
                        <option value="EE">Estonia</option>
                        <option value="ET">Ethiopia</option>
                        <option value="FI">Finland</option>
                        <option value="FR">France</option>
                        <option value="GE">Georgia</option>
                        <option value="DE">Germany</option>
                        <option value="GH">Ghana</option>
                        <option value="GR">Greece</option>
                        <option value="GT">Guatemala</option>
                        <option value="HK">Hong Kong</option>
                        <option value="HU">Hungary</option>
                        <option value="IS">Iceland</option>
                        <option value="IN">India</option>
                        <option value="ID">Indonesia</option>
                        <option value="IR">Iran</option>
                        <option value="IQ">Iraq</option>
                        <option value="IE">Ireland</option>
                        <option value="IL">Israel</option>
                        <option value="IT">Italy</option>
                        <option value="JM">Jamaica</option>
                        <option value="JP">Japan</option>
                        <option selected value="JO">Jordan</option>
                        <option value="KZ">Kazakhstan</option>
                        <option value="KE">Kenya</option>
                        <option value="KW">Kuwait</option>
                        <option value="KG">Kyrgyzstan</option>
                        <option value="LA">Laos</option>
                        <option value="LV">Latvia</option>
                        <option value="LB">Lebanon</option>
                        <option value="LY">Libya</option>
                        <option value="LT">Lithuania</option>
                        <option value="LU">Luxembourg</option>
                        <option value="MO">Macau</option>
                        <option value="MG">Madagascar</option>
                        <option value="MY">Malaysia</option>
                        <option value="MV">Maldives</option>
                        <option value="MT">Malta</option>
                        <option value="MX">Mexico</option>
                        <option value="MD">Moldova</option>
                        <option value="MN">Mongolia</option>
                        <option value="ME">Montenegro</option>
                        <option value="MA">Morocco</option>
                        <option value="MM">Myanmar</option>
                        <option value="NP">Nepal</option>
                        <option value="NL">Netherlands</option>
                        <option value="NZ">New Zealand</option>
                        <option value="NG">Nigeria</option>
                        <option value="KP">North Korea</option>
                        <option value="MK">North Macedonia</option>
                        <option value="NO">Norway</option>
                        <option value="OM">Oman</option>
                        <option value="PK">Pakistan</option>
                        <option value="PS">Palestine</option>
                        <option value="PA">Panama</option>
                        <option value="PY">Paraguay</option>
                        <option value="PE">Peru</option>
                        <option value="PH">Philippines</option>
                        <option value="PL">Poland</option>
                        <option value="PT">Portugal</option>
                        <option value="PR">Puerto Rico</option>
                        <option value="QA">Qatar</option>
                        <option value="RO">Romania</option>
                        <option value="RU">Russia</option>
                        <option value="RW">Rwanda</option>
                        <option value="SA">Saudi Arabia</option>
                        <option value="SN">Senegal</option>
                        <option value="RS">Serbia</option>
                        <option value="SG">Singapore</option>
                        <option value="SK">Slovakia</option>
                        <option value="SI">Slovenia</option>
                        <option value="ZA">South Africa</option>
                        <option value="KR">South Korea</option>
                        <option value="ES">Spain</option>
                        <option value="LK">Sri Lanka</option>
                        <option value="SD">Sudan</option>
                        <option value="SE">Sweden</option>
                        <option value="CH">Switzerland</option>
                        <option value="SY">Syria</option>
                        <option value="TW">Taiwan</option>
                        <option value="TJ">Tajikistan</option>
                        <option value="TZ">Tanzania</option>
                        <option value="TH">Thailand</option>
                        <option value="TN">Tunisia</option>
                        <option value="TR">Turkey</option>
                        <option value="TM">Turkmenistan</option>
                        <option value="UG">Uganda</option>
                        <option value="UA">Ukraine</option>
                        <option value="AE">United Arab Emirates</option>
                        <option value="GB">United Kingdom</option>
                        <option value="US">United States</option>
                        <option value="UY">Uruguay</option>
                        <option value="UZ">Uzbekistan</option>
                        <option value="VE">Venezuela</option>
                        <option value="VN">Vietnam</option>
                        <option value="YE">Yemen</option>
                        <option value="ZM">Zambia</option>
                        <option value="ZW">Zimbabwe</option>
                    </select>
                    <svg class="form-select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
                <div class="form-hint">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    Tax will be calculated based on your billing country regulations.
                </div>
            </div>

            <!-- Payment Method -->
            <div class="section">
                <div class="section-header">
                    <div class="section-label">Payment Method</div>
                    <div class="section-title">Choose how to pay</div>
                </div>
                <div class="payment-options">
                    <div class="payment-option selected" onclick="selectPayment('card', this)">
                        <div class="payment-option-left">
                            <div class="payment-radio"></div>
                            <span class="payment-name">Credit or Debit Card</span>
                        </div>
                        <div class="payment-badges" style="display: flex; gap: 8px; align-items: center;">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/9/98/Visa_Inc._logo_%282005%E2%80%932014%29.svg" alt="Visa" style="height: 12px; width: auto; filter: grayscale(0.2) contrast(1.1);">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a4/Mastercard_2019_logo.svg" alt="Mastercard" style="height: 16px; width: auto;">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/American_Express_logo_%282018%29.svg" alt="Amex" style="height: 14px; width: auto;">
                        </div>
                    </div>
                    <div class="payment-option" onclick="selectPayment('paypal', this)">
                        <div class="payment-option-left">
                            <div class="payment-radio"></div>
                            <span class="payment-name">PayPal</span>
                        </div>
                        <div class="payment-badges" style="display: flex; align-items: center;">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" style="height: 16px; width: auto;">
                        </div>
                    </div>
                    <div class="payment-option" onclick="selectPayment('googlepay', this)">
                        <div class="payment-option-left">
                            <div class="payment-radio"></div>
                            <span class="payment-name">Google Pay</span>
                        </div>
                        <div class="payment-badges" style="display: flex; align-items: center;">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/f/f2/Google_Pay_Logo.svg" alt="Google Pay" style="height: 18px; width: auto;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="section">
                <div class="section-header">
                    <div class="section-label">Order Details</div>
                    <div class="section-title">Your plan</div>
                </div>
                <div class="order-items">
                    <div class="order-item">
                        <div class="order-item-thumb">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                            </svg>
                        </div>
                        <div class="order-item-info">
                            <div class="order-item-name">Pro Plan</div>
                            <div class="order-item-meta">Billed monthly • Cancel anytime</div>
                        </div>
                        <div class="order-item-price">
                            <div class="order-item-price-current">$19.00</div>
                            <div class="order-item-price-original">$49.99</div>
                        </div>
                    </div>
                    <ul class="feature-list">
                        <li>Unlimited AI Roadmaps</li>
                        <li>Unlimited AI Mentor</li>
                        <li>Full video library</li>
                        <li>Multi-device sync</li>
                        <li>Team management</li>
                        <li>Activity history</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="checkout-right">
            <div class="summary-header">Summary</div>
            
            <table class="summary-table">
                <tr>
                    <td>Subtotal</td>
                    <td>$49.99</td>
                </tr>
                <tr>
                    <td>Discount (62%)</td>
                    <td>−$30.99</td>
                </tr>
                <tr>
                    <td>Tax</td>
                    <td>Calculated at next step</td>
                </tr>
                <tr class="summary-total">
                    <td>Total due</td>
                    <td>
                        $19.00
                        <span class="savings-tag">Save 62%</span>
                    </td>
                </tr>
            </table>

            <div class="secure-notice">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                Secured by 256-bit encryption
            </div>

            <p class="terms">
                By confirming, you agree to our 
                <a href="/terms" target="_blank">Terms of Service</a> and 
                <a href="/privacy" target="_blank">Privacy Policy</a>.
            </p>

            <div class="checkout-success-message" id="checkoutSuccessMessage" role="status" aria-live="polite">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <div>
                    Payment successful. Activating your Pro access now...
                </div>
            </div>

            <form method="POST" action="{{ route('upgrade.activate') }}" id="checkoutPayForm">
                @csrf
                <input type="hidden" name="phone" value="">
                <input type="hidden" name="payment_method" id="payment_method" value="card">
                <button type="submit" class="btn-pay" id="paySubmitBtn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    Pay $19.00
                </button>
            </form>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    function selectPayment(method, element) {
        // Remove selected class from all options
        document.querySelectorAll('.payment-option').forEach(el => {
            el.classList.remove('selected');
        });
        
        // Add selected class to clicked option
        element.classList.add('selected');
        
        // Update hidden input
        document.getElementById('payment_method').value = method;
    }

    let checkoutSubmitting = false;

    document.getElementById('checkoutPayForm').addEventListener('submit', function(e) {
        if (checkoutSubmitting) return;

        e.preventDefault();
        checkoutSubmitting = true;

        const btn = document.getElementById('paySubmitBtn');
        const successMessage = document.getElementById('checkoutSuccessMessage');

        if (successMessage) {
            successMessage.classList.add('show');
        }

        if (typeof showToast === 'function') {
            showToast('Payment successful. Activating Pro access...', 'success');
        }

        btn.disabled = true;
        btn.innerHTML = '<div class="spinner"></div> Processing...';

        setTimeout(() => this.submit(), 900);
    });
</script>
@endsection
