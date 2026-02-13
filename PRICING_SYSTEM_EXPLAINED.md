# GlobalSkyFleet Pricing & Invoicing System Explained

## 📋 Overview
When a customer creates a shipment, the system automatically calculates the shipping cost and generates an invoice based on multiple factors.

**🚨 IMPORTANT: GLOBAL MINIMUM CHARGE = $500**
- **ALL invoices have a minimum of $500**, regardless of service type
- If calculated total is less than $500, a "Minimum service charge" is added
- This ensures premium service quality

---

## 💰 How Pricing Works

### 1. **Base Pricing Structure**

The pricing is calculated in the `createInvoiceForShipment()` method (lines 289-435 in ShipmentController.php).

#### Service-Based Rates:

| Service Type | Rate Structure | Minimum Charge | Example (1kg) |
|--------------|----------------|----------------|---------------|
| **Express (EXP)** | $25 per kg | **$500** | 1kg × $25 = $25 → **$500** |
| **Economy (ECO)** | $12 per kg | **$500** | 1kg × $12 = $12 → **$500** |
| **Freight (FRT)** | $8 per kg | **$500** | 1kg × $8 = $8 → **$500** |
| **Documents (DOC)** | $15 flat rate | **$500** | $15 → **$500** |

**Note:** For heavy shipments, the weight-based calculation may exceed $500. For example:
- Express: 25kg × $25/kg = $625 (no minimum applied)
- Economy: 50kg × $12/kg = $600 (no minimum applied)

### 2. **Calculation Formula**

```
Base Price = Weight (kg) × Rate Per Kg
Then: Apply $500 minimum if total is less
```

**Example 1 - Light Package:**
- Service: Economy Shipping
- Weight: 3.5 kg
- Calculation: 3.5 kg × $12/kg = $42
- **After $500 minimum applied: $500.00**
- Invoice shows: "$42 Economy Shipping" + "$458 Minimum service charge" = **$500**

**Example 2 - Heavy Package:**
- Service: Express
- Weight: 30 kg
- Calculation: 30 kg × $25/kg = $750
- **Final Price: $750.00** (already above $500 minimum)
- No minimum charge adjustment needed

---

## 📊 Additional Fees

After the base price, the system adds optional fees:

### 1. **Insurance Fee** (Optional)
```
Insurance Fee = Insurance Amount × 3%
```

**Example:**
- Customer insures package for $1,000
- Insurance fee: $1,000 × 0.03 = $30

**Code:**
```php
if ($shipment->insurance_enabled && $shipment->insurance_amount > 0) {
    $insuranceFee = $shipment->insurance_amount * 0.03; // 3% of insured value
    $basePrice += $insuranceFee;
}
```

### 2. **Signature on Delivery** (Optional)
```
Signature Fee = Fixed $20
```

**Code:**
```php
if ($shipment->requires_signature) {
    $signatureFee = 20;
    $basePrice += $signatureFee;
}
```

### 3. **Dangerous Goods Handling** (Optional)
```
Dangerous Goods Fee = Fixed $75
```

**Code:**
```php
if ($shipment->is_dangerous_goods) {
    $dangerousGoodsFee = 75;
    $basePrice += $dangerousGoodsFee;
}
```

---

## 🧮 Complete Pricing Example

Let's walk through a **real scenario**:

### Scenario 1: Light Package (Minimum Applied)

**Customer Details:**
- **Service:** Economy Shipping
- **Weight:** 2 kg
- **Insurance:** No
- **Signature Required:** No
- **Dangerous Goods:** No

**Calculation:**

```
Step 1: Base Shipping
  - Rate: $12/kg
  - Weight: 2 kg
  - Subtotal: 2 × $12 = $24

Step 2: Insurance
  - Fee: $0 (not selected)

Step 3: Signature
  - Fee: $0 (not selected)

Step 4: Dangerous Goods
  - Fee: $0 (not selected)

Subtotal = $24

Step 5: Apply $500 Global Minimum
  - Current total: $24
  - Minimum required: $500
  - Adjustment needed: $500 - $24 = $476
  - Add line item: "Minimum service charge: $476"

FINAL INVOICE AMOUNT = $500.00
```

**Invoice Breakdown:**
```
Economy Shipping (2kg)          $24.00
Minimum Service Charge         $476.00
────────────────────────────────────
TOTAL                          $500.00
```

---

### Scenario 2: Heavy Package (Above Minimum)

**Customer Details:**
- **Service:** Express Shipping
- **Weight:** 25 kg
- **Insurance:** Yes ($2,000 coverage)
- **Signature Required:** Yes
- **Dangerous Goods:** No

**Calculation:**

```
Step 1: Base Shipping
  - Rate: $25/kg
  - Weight: 25 kg
  - Subtotal: 25 × $25 = $625 ✓ (above $500)

Step 2: Insurance
  - Coverage: $2,000
  - Fee: $2,000 × 3% = $60

Step 3: Signature
  - Fee: $20

Step 4: Dangerous Goods
  - Fee: $0 (not selected)

Subtotal = $625 + $60 + $20 = $705

Step 5: Check $500 Global Minimum
  - Current total: $705
  - Minimum required: $500
  - ✓ Already above minimum, no adjustment needed

FINAL INVOICE AMOUNT = $705.00
```

**Invoice Breakdown:**
```
Express Shipping (25kg)        $625.00
Insurance ($2,000)              $60.00
Signature on Delivery           $20.00
────────────────────────────────────
TOTAL                          $705.00
```

---

## 🧾 Invoice Generation Process

### Step-by-Step Flow:

1. **User Creates Shipment**
   - Fills out shipment form
   - Selects service type, weight, options
   - Submits form

2. **System Validates**
   - Checks weight against service limits
   - Validates addresses
   - Validates all input data

3. **Shipment Created**
   - Generates unique tracking number (e.g., `GSAB12XY89`)
   - Saves shipment to database
   - Status: `pending`

4. **Invoice Auto-Generated**
   - `createInvoiceForShipment()` is called
   - Calculates base price + fees
   - Creates itemized invoice

5. **Invoice Details Stored:**
   ```php
   Invoice::create([
       'user_id' => $shipment->user_id,
       'invoice_number' => 'INV-20260131-ABC123',
       'amount' => $205.00,
       'currency' => 'USD',
       'description' => 'Shipping Service - Express - Tracking #GSAB12XY89',
       'invoice_date' => now(),
       'due_date' => now()->addDays(7), // 7 days to pay
       'status' => 'pending',
       'items' => [itemized breakdown]
   ]);
   ```

6. **User Redirected to Payment**
   - Automatically redirected to billing page
   - Can pay via cryptocurrency (USDT)

---

## 📄 Invoice Items Breakdown

The invoice includes an **itemized list** of charges:

### Example Invoice Items:
```json
[
  {
    "description": "Express shipping for 5kg",
    "quantity": 1,
    "unit_price": 125.00,
    "amount": 125.00
  },
  {
    "description": "Insurance coverage ($2,000.00)",
    "quantity": 1,
    "unit_price": 60.00,
    "amount": 60.00
  },
  {
    "description": "Signature on delivery",
    "quantity": 1,
    "unit_price": 20.00,
    "amount": 20.00
  }
]
```

**Total:** $205.00

---

## 🔄 Payment Flow

```
1. Shipment Created → Invoice Generated (Status: Pending)
                     ↓
2. User Redirected → Billing Page
                     ↓
3. User Selects → Cryptocurrency Payment (USDT)
                     ↓
4. Admin Provides → Crypto Wallet Address
                     ↓
5. User Pays → Uploads Payment Proof
                     ↓
6. Admin Verifies → Invoice Status: Paid
                     ↓
7. Shipment Activated → Status: Confirmed → Processing Begins
```

---

## ⚙️ How to Modify Pricing

### To Change Rates:

Edit `ShipmentController.php` around **lines 296-324**:

```php
switch($service->code) {
    case 'EXP': // Express
        $ratePerKg = 25;  // ← CHANGE THIS
        $basePrice = $weight * $ratePerKg;
        $minimum = 50;    // ← CHANGE MINIMUM
        break;
        
    case 'ECO': // Economy
        $ratePerKg = 12;  // ← CHANGE THIS
        $basePrice = $weight * $ratePerKg;
        $minimum = 25;    // ← CHANGE MINIMUM
        break;
    
    // ... etc
}
```

### To Change Optional Fees:

```php
// Insurance (line 334)
$insuranceFee = $shipment->insurance_amount * 0.03; // Change 0.03 (3%)

// Signature (line 341)
$signatureFee = 20; // Change flat fee

// Dangerous Goods (line 348)
$dangerousGoodsFee = 75; // Change flat fee
```

---

## 🎯 Key Features

### ✅ Automatic Calculation
- No manual input needed
- Instant pricing quote
- Transparent breakdown

### ✅ Itemized Invoices
- Shows exactly what customer pays for
- Professional invoice format
- Includes all fees

### ✅ Flexible Payment Terms
- 7-day payment window
- Invoice status tracking
- Crypto payment support

### ✅ Service Validation
- Weight limits enforced per service
- Prevents over-weight shipments
- Service availability checks

---

## 📊 Database Schema

### Invoices Table:
```
- id (primary key)
- user_id (who owes the money)
- invoice_number (unique identifier)
- amount (total to pay)
- currency (USD, EUR, etc.)
- description (what it's for)
- invoice_date (when created)
- due_date (when payment is due)
- status (pending/paid/overdue)
- items (JSON array of line items)
```

### Shipments Table:
```
- invoice_id (links to invoice)
- All shipment details
```

---

## 🔍 Where to Find in Code

| Feature | File | Lines |
|---------|------|-------|
| **Invoice Creation** | `ShipmentController.php` | 289-417 |
| **Price Calculation** | `ShipmentController.php` | 291-350 |
| **Service Rates** | `ShipmentController.php` | 296-324 |
| **Additional Fees** | `ShipmentController.php` | 332-350 |
| **Invoice Items** | `ShipmentController.php` | 356-394 |
| **Shipment Store** | `ShipmentController.php` | 120-284 |

---

## 💡 Summary

**The system automatically:**
1. Takes customer's shipment details (weight, service, options)
2. Calculates base price using service-specific rates
3. Adds any optional fees (insurance, signature, dangerous goods)
4. Generates a professional itemized invoice
5. Creates a unique invoice number
6. Links invoice to shipment
7. Redirects customer to payment page

**The customer pays the exact amount calculated based on:**
- Weight of package
- Selected service type
- Optional add-ons chosen
- No hidden fees!

---

## 🎓 Want to Change Something?

1. **Change base rates** → Edit service rate table in `ShipmentController.php`
2. **Change optional fees** → Edit fee calculations (lines 332-350)
3. **Change payment terms** → Edit `due_date` calculation (line 407)
4. **Add new fees** → Add new conditional logic similar to existing fees

All pricing is **transparent**, **automatic**, and **itemized** for the customer!
