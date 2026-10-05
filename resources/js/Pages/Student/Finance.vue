<template>
  <AppLayout>
    <div class="student-finance">
      <div class="page-header">
        <h1>My Finance</h1>
        <p>Track your fees, payments, and financial status</p>
      </div>
      
      <div class="finance-overview">
        <div class="balance-card">
          <div class="balance-header">
            <h3>Current Balance</h3>
            <div class="balance-amount" :class="{ 'positive': balance >= 0, 'negative': balance < 0 }">
              ${{ Math.abs(balance).toFixed(2) }}
              <span v-if="balance < 0" class="balance-status">Due</span>
              <span v-else-if="balance > 0" class="balance-status">Overpaid</span>
              <span v-else class="balance-status">Paid</span>
            </div>
          </div>
          
          <div class="balance-details">
            <div class="detail-item">
              <span>Total Fees:</span>
              <span>${{ totalFees.toFixed(2) }}</span>
            </div>
            <div class="detail-item">
              <span>Total Paid:</span>
              <span>${{ totalPaid.toFixed(2) }}</span>
            </div>
            <div class="detail-item">
              <span>Pending Payments:</span>
              <span>${{ pendingPayments.toFixed(2) }}</span>
            </div>
          </div>
          
          <button 
            v-if="balance > 0" 
            class="btn-primary pay-btn"
            @click="makePayment"
          >
            Make Payment
          </button>
        </div>
        
        <div class="payment-methods">
          <h3>Payment Methods</h3>
          <div class="methods-grid">
            <div 
              v-for="method in paymentMethods" 
              :key="method.id"
              class="method-card"
              :class="{ 'active': selectedPaymentMethod === method.id }"
              @click="selectedPaymentMethod = method.id"
            >
              <i :class="method.icon"></i>
              <span>{{ method.name }}</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="finance-tabs">
        <button 
          :class="{ active: activeTab === 'invoices' }"
          @click="activeTab = 'invoices'"
        >
          Invoices
        </button>
        <button 
          :class="{ active: activeTab === 'payments' }"
          @click="activeTab = 'payments'"
        >
          Payment History
        </button>
        <button 
          :class="{ active: activeTab === 'statements' }"
          @click="activeTab = 'statements'"
        >
          Statements
        </button>
      </div>
      
      <!-- Invoices Tab -->
      <div v-if="activeTab === 'invoices'" class="invoices-tab">
        <div class="filters">
          <div class="filter-group">
            <label for="status-filter">Status:</label>
            <select id="status-filter" v-model="invoiceStatusFilter">
              <option value="">All</option>
              <option value="pending">Pending</option>
              <option value="paid">Paid</option>
              <option value="overdue">Overdue</option>
            </select>
          </div>
          
          <div class="filter-group">
            <label for="date-filter">Date Range:</label>
            <input type="date" id="date-filter" v-model="invoiceStartDate">
            <span>to</span>
            <input type="date" v-model="invoiceEndDate">
          </div>
        </div>
        
        <div class="invoices-grid">
          <div 
            v-for="invoice in filteredInvoices" 
            :key="invoice.id"
            class="invoice-card"
            :class="invoice.status"
          >
            <div class="invoice-header">
              <h4>{{ invoice.title }}</h4>
              <span class="invoice-status" :class="invoice.status">
                {{ invoice.status }}
              </span>
            </div>
            
            <div class="invoice-details">
              <div class="detail-row">
                <span>Invoice #:</span>
                <span>{{ invoice.number }}</span>
              </div>
              <div class="detail-row">
                <span>Due Date:</span>
                <span>{{ formatDate(invoice.dueDate) }}</span>
              </div>
              <div class="detail-row">
                <span>Amount:</span>
                <span class="amount">${{ invoice.amount.toFixed(2) }}</span>
              </div>
            </div>
            
            <div class="invoice-footer">
              <button 
                v-if="invoice.status === 'pending' || invoice.status === 'overdue'"
                class="btn-primary pay-btn"
                @click="payInvoice(invoice)"
              >
                Pay Now
              </button>
              <button class="btn-secondary view-btn" @click="viewInvoice(invoice)">
                View Details
              </button>
            </div>
          </div>
          
          <div v-if="filteredInvoices.length === 0" class="no-invoices">
            <i class="fas fa-file-invoice-dollar"></i>
            <p>No invoices found</p>
          </div>
        </div>
      </div>
      
      <!-- Payment History Tab -->
      <div v-else-if="activeTab === 'payments'" class="payments-tab">
        <div class="payments-table">
          <div class="table-header">
            <div class="header-cell">Date</div>
            <div class="header-cell">Description</div>
            <div class="header-cell">Method</div>
            <div class="header-cell">Amount</div>
            <div class="header-cell">Status</div>
            <div class="header-cell">Actions</div>
          </div>
          
          <div 
            v-for="payment in payments" 
            :key="payment.id"
            class="table-row"
          >
            <div class="table-cell">{{ formatDate(payment.date) }}</div>
            <div class="table-cell">{{ payment.description }}</div>
            <div class="table-cell">
              <i :class="payment.method.icon"></i>
              {{ payment.method.name }}
            </div>
            <div class="table-cell amount">${{ payment.amount.toFixed(2) }}</div>
            <div class="table-cell">
              <span class="status-badge" :class="payment.status">
                {{ payment.status }}
              </span>
            </div>
            <div class="table-cell">
              <button class="btn-icon" @click="viewReceipt(payment)">
                <i class="fas fa-receipt"></i>
              </button>
            </div>
          </div>
          
          <div v-if="payments.length === 0" class="no-payments">
            <i class="fas fa-money-bill-wave"></i>
            <p>No payment history available</p>
          </div>
        </div>
      </div>
      
      <!-- Statements Tab -->
      <div v-else class="statements-tab">
        <div class="statement-controls">
          <div class="control-group">
            <label for="statement-period">Period:</label>
            <select id="statement-period" v-model="statementPeriod">
              <option value="monthly">Monthly</option>
              <option value="quarterly">Quarterly</option>
              <option value="yearly">Yearly</option>
            </select>
          </div>
          
          <button class="btn-primary" @click="generateStatement">
            <i class="fas fa-download"></i>
            Download Statement
          </button>
        </div>
        
        <div class="statement-preview">
          <div class="statement-header">
            <h3>Financial Statement</h3>
            <p>Period: {{ statementPeriodLabel }}</p>
          </div>
          
          <div class="statement-summary">
            <div class="summary-row">
              <span>Total Fees:</span>
              <span>${{ statementData.totalFees.toFixed(2) }}</span>
            </div>
            <div class="summary-row">
              <span>Total Payments:</span>
              <span>${{ statementData.totalPayments.toFixed(2) }}</span>
            </div>
            <div class="summary-row">
              <span>Adjustments:</span>
              <span>${{ statementData.adjustments.toFixed(2) }}</span>
            </div>
            <div class="summary-row total">
              <span>Balance:</span>
              <span>${{ statementData.balance.toFixed(2) }}</span>
            </div>
          </div>
          
          <div class="statement-details">
            <h4>Transaction Details</h4>
            <div 
              v-for="transaction in statementData.transactions" 
              :key="transaction.id"
              class="transaction-row"
            >
              <div class="transaction-info">
                <div class="transaction-date">{{ formatDate(transaction.date) }}</div>
                <div class="transaction-description">{{ transaction.description }}</div>
              </div>
              <div class="transaction-amount" :class="transaction.type">
                {{ transaction.type === 'debit' ? '-' : '+' }}${{ transaction.amount.toFixed(2) }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

// Reactive data
const activeTab = ref('invoices');
const selectedPaymentMethod = ref('card');
const invoiceStatusFilter = ref('');
const invoiceStartDate = ref(new Date(new Date().setMonth(new Date().getMonth() - 6)).toISOString().split('T')[0]);
const invoiceEndDate = ref(new Date().toISOString().split('T')[0]);
const statementPeriod = ref('monthly');

// Mock data - in a real app, this would come from API
const balance = ref(-150.00);
const totalFees = ref(2500.00);
const totalPaid = ref(2350.00);
const pendingPayments = ref(150.00);

const invoices = ref([
  {
    id: 1,
    number: 'INV-2025-001',
    title: 'Tuition Fee - September',
    amount: 500.00,
    dueDate: '2025-09-30',
    status: 'pending',
    description: 'Monthly tuition fee for September'
  },
  {
    id: 2,
    number: 'INV-2025-002',
    title: 'Library Fee',
    amount: 50.00,
    dueDate: '2025-09-15',
    status: 'overdue',
    description: 'Annual library fee'
  },
  {
    id: 3,
    number: 'INV-2025-003',
    title: 'Tuition Fee - August',
    amount: 500.00,
    dueDate: '2025-08-31',
    status: 'paid',
    description: 'Monthly tuition fee for August'
  },
  {
    id: 4,
    number: 'INV-2025-004',
    title: 'Lab Fee',
    amount: 100.00,
    dueDate: '2025-10-15',
    status: 'pending',
    description: 'Science lab usage fee'
  },
  {
    id: 5,
    number: 'INV-2025-005',
    title: 'Tuition Fee - July',
    amount: 500.00,
    dueDate: '2025-07-31',
    status: 'paid',
    description: 'Monthly tuition fee for July'
  },
  {
    id: 6,
    number: 'INV-2025-006',
    title: 'Sports Fee',
    amount: 75.00,
    dueDate: '2025-09-30',
    status: 'pending',
    description: 'Annual sports facility fee'
  }
]);

const payments = ref([
  {
    id: 1,
    date: '2025-09-01',
    description: 'Tuition Fee - July',
    amount: 500.00,
    status: 'completed',
    method: {
      id: 'card',
      name: 'Credit Card',
      icon: 'fas fa-credit-card'
    },
    receiptUrl: '#'
  },
  {
    id: 2,
    date: '2025-08-15',
    description: 'Tuition Fee - June',
    amount: 500.00,
    status: 'completed',
    method: {
      id: 'bank',
      name: 'Bank Transfer',
      icon: 'fas fa-university'
    },
    receiptUrl: '#'
  },
  {
    id: 3,
    date: '2025-07-20',
    description: 'Library Fee',
    amount: 50.00,
    status: 'completed',
    method: {
      id: 'mpesa',
      name: 'M-Pesa',
      icon: 'fas fa-mobile-alt'
    },
    receiptUrl: '#'
  },
  {
    id: 4,
    date: '2025-07-01',
    description: 'Tuition Fee - May',
    amount: 500.00,
    status: 'completed',
    method: {
      id: 'card',
      name: 'Credit Card',
      icon: 'fas fa-credit-card'
    },
    receiptUrl: '#'
  },
  {
    id: 5,
    date: '2025-06-10',
    description: 'Tuition Fee - April',
    amount: 500.00,
    status: 'completed',
    method: {
      id: 'card',
      name: 'Credit Card',
      icon: 'fas fa-credit-card'
    },
    receiptUrl: '#'
  }
]);

const paymentMethods = ref([
  {
    id: 'card',
    name: 'Credit Card',
    icon: 'fas fa-credit-card'
  },
  {
    id: 'bank',
    name: 'Bank Transfer',
    icon: 'fas fa-university'
  },
  {
    id: 'mpesa',
    name: 'M-Pesa',
    icon: 'fas fa-mobile-alt'
  },
  {
    id: 'paypal',
    name: 'PayPal',
    icon: 'fab fa-paypal'
  }
]);

const statementData = ref({
  totalFees: 2500.00,
  totalPayments: 2350.00,
  adjustments: 0.00,
  balance: -150.00,
  transactions: [
    {
      id: 1,
      date: '2025-09-01',
      description: 'Tuition Fee - July',
      amount: 500.00,
      type: 'credit'
    },
    {
      id: 2,
      date: '2025-08-15',
      description: 'Tuition Fee - June',
      amount: 500.00,
      type: 'credit'
    },
    {
      id: 3,
      date: '2025-07-20',
      description: 'Library Fee',
      amount: 50.00,
      type: 'credit'
    },
    {
      id: 4,
      date: '2025-07-01',
      description: 'Tuition Fee - May',
      amount: 500.00,
      type: 'credit'
    },
    {
      id: 5,
      date: '2025-06-10',
      description: 'Tuition Fee - April',
      amount: 500.00,
      type: 'credit'
    },
    {
      id: 6,
      date: '2025-09-01',
      description: 'Tuition Fee - September',
      amount: 500.00,
      type: 'debit'
    }
  ]
});

// Computed properties
const filteredInvoices = computed(() => {
  return invoices.value.filter(invoice => {
    const invoiceDate = new Date(invoice.dueDate);
    const start = new Date(invoiceStartDate.value);
    const end = new Date(invoiceEndDate.value);
    
    const dateInRange = invoiceDate >= start && invoiceDate <= end;
    const statusMatch = invoiceStatusFilter.value ? invoice.status === invoiceStatusFilter.value : true;
    
    return dateInRange && statusMatch;
  });
});

const statementPeriodLabel = computed(() => {
  const today = new Date();
  switch (statementPeriod.value) {
    case 'monthly':
      return `${today.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}`;
    case 'quarterly':
      const quarter = Math.floor(today.getMonth() / 3) + 1;
      return `Q${quarter} ${today.getFullYear()}`;
    case 'yearly':
      return `${today.getFullYear()}`;
    default:
      return `${today.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}`;
  }
});

// Methods
const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

const makePayment = () => {
  alert('Payment processing would start here');
};

const payInvoice = (invoice) => {
  alert(`Processing payment for invoice: ${invoice.number}`);
};

const viewInvoice = (invoice) => {
  alert(`Viewing details for invoice: ${invoice.number}`);
};

const viewReceipt = (payment) => {
  alert(`Viewing receipt for payment: ${payment.description}`);
};

const generateStatement = () => {
  alert(`Generating ${statementPeriod.value} statement`);
};

// Lifecycle
onMounted(() => {
  // In a real app, we would fetch data from the API here
});
</script>

<style scoped>
.student-finance {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.page-header {
  margin-bottom: 30px;
}

.page-header h1 {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 10px;
  color: #1f2937;
}

.page-header p {
  font-size: 1.1rem;
  color: #6b7280;
}

.finance-overview {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 20px;
  margin-bottom: 30px;
}

.balance-card {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.balance-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.balance-header h3 {
  font-size: 1.2rem;
  font-weight: 600;
  color: #1f2937;
}

.balance-amount {
  font-size: 2rem;
  font-weight: 700;
}

.balance-amount.positive {
  color: #22c55e;
}

.balance-amount.negative {
  color: #ef4444;
}

.balance-status {
  font-size: 1rem;
  font-weight: 500;
  margin-left: 10px;
  padding: 4px 8px;
  border-radius: 20px;
}

.balance-status.Due {
  background: #fee2e2;
  color: #991b1b;
}

.balance-status.Overpaid {
  background: #dcfce7;
  color: #166534;
}

.balance-status.Paid {
  background: #dbeafe;
  color: #1e40af;
}

.balance-details {
  margin-bottom: 20px;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
  border-bottom: 1px solid #e5e7eb;
}

.detail-item:last-child {
  border-bottom: none;
}

.detail-item span:first-child {
  color: #6b7280;
}

.detail-item span:last-child {
  font-weight: 500;
  color: #1f2937;
}

.pay-btn {
  width: 100%;
  padding: 12px;
  font-size: 1rem;
}

.payment-methods {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.payment-methods h3 {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 20px;
  color: #1f2937;
}

.methods-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 15px;
}

.method-card {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 15px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.method-card:hover {
  border-color: #3b82f6;
  background: #f0f9ff;
}

.method-card.active {
  border-color: #3b82f6;
  background: #eff6ff;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
}

.method-card i {
  font-size: 1.5rem;
  color: #3b82f6;
}

.finance-tabs {
  display: flex;
  background: #f3f4f6;
  border-radius: 8px;
  padding: 4px;
  margin-bottom: 30px;
}

.finance-tabs button {
  flex: 1;
  background: none;
  border: none;
  padding: 12px 16px;
  border-radius: 6px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
}

.finance-tabs button.active {
  background: white;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Invoices Tab */
.invoices-tab {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.filters {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.filter-group {
  display: flex;
  align-items: center;
  gap: 10px;
}

.filter-group label {
  font-weight: 500;
  color: #1f2937;
  white-space: nowrap;
}

.filter-group input,
.filter-group select {
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  min-width: 120px;
}

.invoices-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}

.invoice-card {
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 20px;
  transition: all 0.2s ease;
}

.invoice-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  border-color: #d1d5db;
}

.invoice-card.pending {
  border-left: 4px solid #f59e0b;
}

.invoice-card.paid {
  border-left: 4px solid #22c55e;
}

.invoice-card.overdue {
  border-left: 4px solid #ef4444;
}

.invoice-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 15px;
}

.invoice-header h4 {
  font-size: 1.1rem;
  font-weight: 600;
  color: #1f2937;
}

.invoice-status {
  padding: 4px 8px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
}

.invoice-status.pending {
  background: #ffedd5;
  color: #9a3412;
}

.invoice-status.paid {
  background: #dcfce7;
  color: #166534;
}

.invoice-status.overdue {
  background: #fee2e2;
  color: #991b1b;
}

.invoice-details {
  margin-bottom: 20px;
}

.detail-row {
  display: flex;
  justify-content: space-between;
  padding: 5px 0;
}

.detail-row span:first-child {
  color: #6b7280;
}

.detail-row .amount {
  font-weight: 600;
  color: #1f2937;
}

.invoice-footer {
  display: flex;
  gap: 10px;
}

.pay-btn {
  flex: 1;
  padding: 8px 12px;
  font-size: 0.9rem;
}

.view-btn {
  flex: 1;
  padding: 8px 12px;
  font-size: 0.9rem;
}

.no-invoices {
  grid-column: 1 / -1;
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
}

.no-invoices i {
  font-size: 3rem;
  margin-bottom: 15px;
  color: #d1d5db;
}

/* Payments Tab */
.payments-tab {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.payments-table {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  overflow: hidden;
}

.table-header {
  display: grid;
  grid-template-columns: 1fr 2fr 1fr 1fr 1fr 0.5fr;
  background: #f9fafb;
  font-weight: 600;
  padding: 12px 15px;
}

.table-row {
  display: grid;
  grid-template-columns: 1fr 2fr 1fr 1fr 1fr 0.5fr;
  padding: 12px 15px;
  border-bottom: 1px solid #e5e7eb;
}

.table-row:last-child {
  border-bottom: none;
}

.table-row:hover {
  background: #f9fafb;
}

.header-cell,
.table-cell {
  padding: 0 5px;
  display: flex;
  align-items: center;
}

.table-cell.amount {
  font-weight: 600;
  color: #1f2937;
}

.btn-icon {
  background: none;
  border: none;
  color: #3b82f6;
  cursor: pointer;
  font-size: 1.1rem;
  padding: 5px;
  border-radius: 4px;
}

.btn-icon:hover {
  background: #dbeafe;
}

.no-payments {
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
}

.no-payments i {
  font-size: 3rem;
  margin-bottom: 15px;
  color: #d1d5db;
}

/* Statements Tab */
.statements-tab {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.statement-controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  flex-wrap: wrap;
  gap: 15px;
}

.control-group {
  display: flex;
  align-items: center;
  gap: 10px;
}

.control-group label {
  font-weight: 500;
  color: #1f2937;
}

.control-group select {
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
}

.statement-preview {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 25px;
}

.statement-header {
  text-align: center;
  margin-bottom: 30px;
}

.statement-header h3 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 10px;
}

.statement-header p {
  color: #6b7280;
}

.statement-summary {
  background: #f9fafb;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 30px;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  border-bottom: 1px solid #e5e7eb;
}

.summary-row:last-child {
  border-bottom: none;
}

.summary-row.total {
  font-weight: 700;
  font-size: 1.1rem;
  color: #1f2937;
}

.statement-details h4 {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 20px;
  color: #1f2937;
}

.transaction-row {
  display: flex;
  justify-content: space-between;
  padding: 12px 0;
  border-bottom: 1px solid #e5e7eb;
}

.transaction-row:last-child {
  border-bottom: none;
}

.transaction-info {
  flex: 1;
}

.transaction-date {
  font-size: 0.9rem;
  color: #6b7280;
  margin-bottom: 5px;
}

.transaction-description {
  font-weight: 500;
  color: #1f2937;
}

.transaction-amount {
  font-weight: 600;
}

.transaction-amount.debit {
  color: #ef4444;
}

.transaction-amount.credit {
  color: #22c55e;
}

/* Dark mode support */
.dark .student-finance {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .balance-header h3,
.dark .payment-methods h3,
.dark .invoice-header h4,
.dark .statement-header h3,
.dark .statement-details h4,
.dark .detail-item span:last-child,
.dark .transaction-description {
  color: #f9fafb;
}

.dark .page-header p,
.dark .detail-item span:first-child,
.dark .transaction-date,
.dark .control-group label,
.dark .filter-group label {
  color: #d1d5db;
}

.dark .balance-card,
.dark .payment-methods,
.dark .invoices-tab,
.dark .payments-tab,
.dark .statements-tab {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .finance-tabs {
  background: #374151;
}

.dark .finance-tabs button.active {
  background: #1f2937;
}

.dark .invoice-card {
  border-color: #374151;
  background: #1f2937;
}

.dark .invoice-card:hover {
  border-color: #4b5563;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.dark .invoice-card.pending {
  border-left-color: #f59e0b;
}

.dark .invoice-card.paid {
  border-left-color: #22c55e;
}

.dark .invoice-card.overdue {
  border-left-color: #ef4444;
}

.dark .payments-table,
.dark .statement-preview {
  border-color: #374151;
}

.dark .table-header {
  background: #111827;
}

.dark .table-row {
  border-bottom-color: #374151;
}

.dark .table-row:hover {
  background: #111827;
}

.dark .btn-icon:hover {
  background: #1e3a8a;
}

.dark .statement-summary {
  background: #111827;
}

.dark .summary-row {
  border-bottom-color: #374151;
}

.dark .transaction-row {
  border-bottom-color: #374151;
}

.dark .method-card {
  border-color: #374151;
  background: #1f2937;
}

.dark .method-card:hover {
  border-color: #3b82f6;
  background: #1e3a8a;
}

.dark .method-card.active {
  border-color: #3b82f6;
  background: #1e3a8a;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
}

.dark .no-invoices,
.dark .no-payments {
  color: #9ca3af;
}

.dark .no-invoices i,
.dark .no-payments i {
  color: #4b5563;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .student-finance {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .finance-overview {
    grid-template-columns: 1fr;
  }
  
  .filters {
    flex-direction: column;
  }
  
  .table-header,
  .table-row {
    grid-template-columns: 1fr 1fr;
    font-size: 0.9rem;
  }
  
  .statement-controls {
    flex-direction: column;
  }
  
  .invoices-grid {
    grid-template-columns: 1fr;
  }
}
</style>