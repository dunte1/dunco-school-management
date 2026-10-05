<template>
  <AppLayout>
    <div class="parent-finance">
      <div class="page-header">
        <h1>Fee Management</h1>
        <p>Manage your child's school fees and payment history</p>
      </div>
      
      <div class="finance-controls">
        <div class="child-selector">
          <label for="child-select">Select Child:</label>
          <select id="child-select" v-model="selectedChild" @change="loadFinanceData">
            <option v-for="child in children" :key="child.id" :value="child.id">
              {{ child.name }}
            </option>
          </select>
        </div>
        
        <button class="btn-primary" @click="makePayment">
          <i class="fas fa-plus"></i>
          Make Payment
        </button>
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
          
          <div class="payment-reminder" v-if="balance > 0">
            <i class="fas fa-exclamation-circle"></i>
            <span>You have outstanding payments due soon</span>
          </div>
        </div>
        
        <div class="payment-methods">
          <h3>Preferred Payment Methods</h3>
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
        <button 
          :class="{ active: activeTab === 'scholarships' }"
          @click="activeTab = 'scholarships'"
        >
          Scholarships
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
            <label for="term-filter">Term:</label>
            <select id="term-filter" v-model="invoiceTermFilter">
              <option value="">All Terms</option>
              <option value="term1">Term 1</option>
              <option value="term2">Term 2</option>
              <option value="term3">Term 3</option>
            </select>
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
                <span>Term:</span>
                <span>{{ invoice.term }}</span>
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
              <div class="days-remaining" v-if="invoice.status === 'pending' || invoice.status === 'overdue'">
                <span v-if="getDaysUntilDue(invoice.dueDate) > 0">
                  {{ getDaysUntilDue(invoice.dueDate) }} days remaining
                </span>
                <span v-else-if="getDaysUntilDue(invoice.dueDate) === 0">
                  Due today
                </span>
                <span v-else class="overdue">
                  {{ Math.abs(getDaysUntilDue(invoice.dueDate)) }} days overdue
                </span>
              </div>
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
            <div class="header-cell">Receipt</div>
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
              <button class="btn-icon" @click="viewReceipt(payment)" title="View Receipt">
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
      <div v-else-if="activeTab === 'statements'" class="statements-tab">
        <div class="statement-controls">
          <div class="control-group">
            <label for="statement-period">Period:</label>
            <select id="statement-period" v-model="statementPeriod">
              <option value="monthly">Monthly</option>
              <option value="termly">Termly</option>
              <option value="yearly">Yearly</option>
            </select>
          </div>
          
          <button class="btn-secondary" @click="downloadStatement">
            <i class="fas fa-download"></i>
            Download Statement
          </button>
        </div>
        
        <div class="statement-preview">
          <div class="statement-header">
            <h3>Fee Statement</h3>
            <p>Period: {{ statementPeriodLabel }}</p>
          </div>
          
          <div class="statement-summary">
            <div class="summary-item">
              <span>Opening Balance:</span>
              <span>${{ statementData.openingBalance.toFixed(2) }}</span>
            </div>
            <div class="summary-item">
              <span>Fees Billed:</span>
              <span>${{ statementData.feesBilled.toFixed(2) }}</span>
            </div>
            <div class="summary-item">
              <span>Payments Received:</span>
              <span class="negative">-${{ statementData.paymentsReceived.toFixed(2) }}</span>
            </div>
            <div class="summary-item">
              <span>Adjustments:</span>
              <span>${{ statementData.adjustments.toFixed(2) }}</span>
            </div>
            <div class="summary-item total">
              <span>Closing Balance:</span>
              <span :class="{ 'positive': statementData.closingBalance < 0, 'negative': statementData.closingBalance > 0 }">
                ${{ Math.abs(statementData.closingBalance).toFixed(2) }}
              </span>
            </div>
          </div>
          
          <div class="statement-details">
            <h4>Transaction Details</h4>
            <div class="transactions-list">
              <div 
                v-for="transaction in statementTransactions" 
                :key="transaction.id"
                class="transaction-item"
              >
                <div class="transaction-info">
                  <div class="transaction-date">{{ formatDate(transaction.date) }}</div>
                  <div class="transaction-description">{{ transaction.description }}</div>
                </div>
                <div class="transaction-amount" :class="transaction.type">
                  {{ transaction.type === 'debit' ? '-' : '' }}${{ transaction.amount.toFixed(2) }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Scholarships Tab -->
      <div v-else class="scholarships-tab">
        <div class="scholarships-overview">
          <div class="scholarship-stats">
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-graduation-cap"></i>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ scholarshipData.totalScholarships }}</div>
                <div class="stat-label">Active Scholarships</div>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-percentage"></i>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ scholarshipData.totalDiscount }}%</div>
                <div class="stat-label">Total Discount</div>
              </div>
            </div>
            
            <div class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-money-bill-wave"></i>
              </div>
              <div class="stat-info">
                <div class="stat-value">${{ scholarshipData.totalSavings.toFixed(2) }}</div>
                <div class="stat-label">Total Savings</div>
              </div>
            </div>
          </div>
          
          <div class="scholarships-list">
            <h3>Active Scholarships</h3>
            <div class="scholarships-grid">
              <div 
                v-for="scholarship in scholarshipData.activeScholarships" 
                :key="scholarship.id"
                class="scholarship-card"
              >
                <div class="scholarship-header">
                  <h4>{{ scholarship.name }}</h4>
                  <span class="scholarship-status">{{ scholarship.status }}</span>
                </div>
                
                <div class="scholarship-details">
                  <div class="detail-item">
                    <span>Provider:</span>
                    <span>{{ scholarship.provider }}</span>
                  </div>
                  <div class="detail-item">
                    <span>Discount:</span>
                    <span>{{ scholarship.discount }}%</span>
                  </div>
                  <div class="detail-item">
                    <span>Expiry:</span>
                    <span>{{ formatDate(scholarship.expiryDate) }}</span>
                  </div>
                  <div class="detail-item">
                    <span>Applied To:</span>
                    <span>{{ scholarship.appliedTo }}</span>
                  </div>
                </div>
                
                <button class="btn-secondary" @click="viewScholarship(scholarship)">
                  View Details
                </button>
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
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Reactive data
const activeTab = ref('invoices');
const selectedChild = ref(1);
const selectedPaymentMethod = ref(1);
const invoiceStatusFilter = ref('');
const invoiceTermFilter = ref('');
const statementPeriod = ref('monthly');

const children = ref([
  { id: 1, name: 'John Doe' },
  { id: 2, name: 'Jane Doe' }
]);

const paymentMethods = ref([
  { id: 1, name: 'Credit Card', icon: 'fas fa-credit-card' },
  { id: 2, name: 'Bank Transfer', icon: 'fas fa-university' },
  { id: 3, name: 'Mobile Money', icon: 'fas fa-mobile-alt' }
]);

// Mock data - in a real app, this would come from API
const financeData = ref({
  balance: -1250.00,
  totalFees: 5000.00,
  totalPaid: 3750.00,
  pendingPayments: 1250.00,
  invoices: [
    {
      id: 1,
      title: 'Term 1 Fees',
      number: 'INV-001',
      term: 'Term 1',
      dueDate: '2025-09-30',
      amount: 1500.00,
      status: 'pending'
    },
    {
      id: 2,
      title: 'Term 2 Fees',
      number: 'INV-002',
      term: 'Term 2',
      dueDate: '2025-12-15',
      amount: 1750.00,
      status: 'pending'
    },
    {
      id: 3,
      title: 'Term 3 Fees',
      number: 'INV-003',
      term: 'Term 3',
      dueDate: '2026-03-30',
      amount: 1750.00,
      status: 'pending'
    },
    {
      id: 4,
      title: 'Library Fees',
      number: 'INV-004',
      term: 'Term 1',
      dueDate: '2025-08-15',
      amount: 150.00,
      status: 'overdue'
    }
  ],
  payments: [
    {
      id: 1,
      date: '2025-08-01',
      description: 'Term 1 Fees Payment',
      amount: 1500.00,
      status: 'completed',
      method: { name: 'Credit Card', icon: 'fas fa-credit-card' },
      receipt: 'receipt_001.pdf'
    },
    {
      id: 2,
      date: '2025-08-05',
      description: 'Registration Fees',
      amount: 500.00,
      status: 'completed',
      method: { name: 'Bank Transfer', icon: 'fas fa-university' },
      receipt: 'receipt_002.pdf'
    },
    {
      id: 3,
      date: '2025-08-10',
      description: 'Uniform Fees',
      amount: 750.00,
      status: 'completed',
      method: { name: 'Mobile Money', icon: 'fas fa-mobile-alt' },
      receipt: 'receipt_003.pdf'
    }
  ],
  statementData: {
    openingBalance: 0.00,
    feesBilled: 5000.00,
    paymentsReceived: 3750.00,
    adjustments: 0.00,
    closingBalance: -1250.00
  },
  statementTransactions: [
    {
      id: 1,
      date: '2025-08-01',
      description: 'Term 1 Fees',
      amount: 1500.00,
      type: 'debit'
    },
    {
      id: 2,
      date: '2025-08-01',
      description: 'Term 1 Fees Payment',
      amount: 1500.00,
      type: 'credit'
    },
    {
      id: 3,
      date: '2025-08-05',
      description: 'Registration Fees',
      amount: 500.00,
      type: 'debit'
    }
  ],
  scholarshipData: {
    totalScholarships: 2,
    totalDiscount: 15,
    totalSavings: 750.00,
    activeScholarships: [
      {
        id: 1,
        name: 'Academic Excellence Scholarship',
        provider: 'School',
        discount: 10,
        expiryDate: '2026-06-30',
        appliedTo: 'Tuition Fees',
        status: 'Active'
      },
      {
        id: 2,
        name: 'Sibling Discount',
        provider: 'School',
        discount: 5,
        expiryDate: '2026-06-30',
        appliedTo: 'All Fees',
        status: 'Active'
      }
    ]
  }
});

// Computed properties
const balance = computed(() => financeData.value.balance);
const totalFees = computed(() => financeData.value.totalFees);
const totalPaid = computed(() => financeData.value.totalPaid);
const pendingPayments = computed(() => financeData.value.pendingPayments);
const filteredInvoices = computed(() => {
  return financeData.value.invoices.filter(invoice => {
    const statusMatch = invoiceStatusFilter.value ? invoice.status === invoiceStatusFilter.value : true;
    const termMatch = invoiceTermFilter.value ? invoice.term === invoiceTermFilter.value : true;
    return statusMatch && termMatch;
  });
});
const payments = computed(() => financeData.value.payments);
const statementData = computed(() => financeData.value.statementData);
const statementTransactions = computed(() => financeData.value.statementTransactions);
const scholarshipData = computed(() => financeData.value.scholarshipData);
const statementPeriodLabel = computed(() => {
  const today = new Date();
  switch (statementPeriod.value) {
    case 'monthly':
      return today.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
    case 'termly':
      return 'Term 1, 2025';
    case 'yearly':
      return '2025';
    default:
      return '';
  }
});

// Methods
const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

const getDaysUntilDue = (dueDate) => {
  const today = new Date();
  const due = new Date(dueDate);
  const diffTime = due - today;
  return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
};

const loadFinanceData = () => {
  // In a real app, this would fetch data from the API for the selected child
  console.log(`Loading finance data for child ID: ${selectedChild.value}`);
};

const makePayment = () => {
  // In a real app, this would open payment modal or navigate to payment page
  alert('Opening payment interface');
};

const payInvoice = (invoice) => {
  // In a real app, this would initiate payment for the selected invoice
  alert(`Processing payment for invoice ${invoice.number}`);
};

const viewInvoice = (invoice) => {
  // In a real app, this would show invoice details
  alert(`Viewing invoice ${invoice.number}`);
};

const viewReceipt = (payment) => {
  // In a real app, this would show or download payment receipt
  alert(`Viewing receipt for payment ${payment.id}`);
};

const downloadStatement = () => {
  // In a real app, this would generate and download statement
  alert('Downloading statement');
};

const viewScholarship = (scholarship) => {
  // In a real app, this would show scholarship details
  alert(`Viewing scholarship ${scholarship.name}`);
};

// Lifecycle
onMounted(() => {
  // In a real app, we would fetch data from the API here
});
</script>

<style scoped>
.parent-finance {
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

.finance-controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  flex-wrap: wrap;
  gap: 15px;
  background: white;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.child-selector {
  display: flex;
  align-items: center;
  gap: 10px;
}

.child-selector label {
  font-weight: 500;
  color: #1f2937;
}

.child-selector select {
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 1rem;
}

.finance-overview {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 30px;
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
  font-size: 1.5rem;
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
  padding: 4px 10px;
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
  display: grid;
  grid-template-columns: 1fr;
  gap: 15px;
  margin-bottom: 20px;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  font-size: 1.1rem;
}

.detail-item span:first-child {
  color: #6b7280;
}

.detail-item span:last-child {
  font-weight: 600;
  color: #1f2937;
}

.payment-reminder {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 15px;
  background: #fffbeb;
  border-radius: 8px;
  color: #92400e;
  border: 1px solid #fbbf24;
}

.payment-methods {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.payment-methods h3 {
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
  border-color: #d1d5db;
  background: #f9fafb;
}

.method-card.active {
  border-color: #3b82f6;
  background: #eff6ff;
}

.method-card i {
  font-size: 1.5rem;
  color: #6b7280;
}

.method-card.active i {
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
}

.filter-group select {
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
}

.invoices-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
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
  border-left: 4px solid #3b82f6;
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
  align-items: center;
  margin-bottom: 15px;
}

.invoice-header h4 {
  font-size: 1.2rem;
  font-weight: 600;
  color: #1f2937;
}

.invoice-status {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 500;
}

.invoice-status.pending {
  background: #dbeafe;
  color: #1e40af;
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
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin-bottom: 15px;
}

.detail-row {
  display: flex;
  justify-content: space-between;
  font-size: 0.9rem;
}

.detail-row span:first-child {
  color: #6b7280;
}

.detail-row span:last-child {
  font-weight: 500;
  color: #1f2937;
}

.amount {
  font-weight: 700;
  color: #1f2937;
}

.invoice-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 10px;
}

.days-remaining {
  font-size: 0.9rem;
  font-weight: 500;
  color: #6b7280;
}

.days-remaining .overdue {
  color: #ef4444;
}

.pay-btn {
  display: flex;
  align-items: center;
  gap: 8px;
}

.no-invoices {
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
  grid-column: 1 / -1;
}

.no-invoices i {
  font-size: 3rem;
  margin-bottom: 15px;
  color: #d1d5db;
}

/* Payment History Tab */
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
  grid-template-columns: 1fr 2fr 1fr 1fr 1fr 1fr;
  background: #f9fafb;
  font-weight: 600;
  padding: 12px 15px;
}

.table-row {
  display: grid;
  grid-template-columns: 1fr 2fr 1fr 1fr 1fr 1fr;
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
}

.table-cell.amount {
  font-weight: 600;
}

.status-badge {
  padding: 4px 8px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
}

.status-badge.completed {
  background: #dcfce7;
  color: #166534;
}

.status-badge.pending {
  background: #ffedd5;
  color: #9a3412;
}

.status-badge.failed {
  background: #fee2e2;
  color: #991b1b;
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
  border-radius: 12px;
  padding: 30px;
}

.statement-header {
  text-align: center;
  margin-bottom: 30px;
}

.statement-header h3 {
  font-size: 1.8rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 10px;
}

.statement-header p {
  font-size: 1.1rem;
  color: #6b7280;
}

.statement-summary {
  display: grid;
  grid-template-columns: 1fr;
  gap: 15px;
  margin-bottom: 30px;
  padding: 20px;
  background: #f9fafb;
  border-radius: 8px;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  font-size: 1.1rem;
}

.summary-item span:first-child {
  color: #6b7280;
}

.summary-item span:last-child {
  font-weight: 600;
  color: #1f2937;
}

.summary-item.total {
  border-top: 1px solid #e5e7eb;
  padding-top: 15px;
  margin-top: 15px;
  font-size: 1.3rem;
}

.summary-item.total span:last-child {
  font-weight: 700;
}

.summary-item.total .positive {
  color: #22c55e;
}

.summary-item.total .negative {
  color: #ef4444;
}

.statement-details h4 {
  font-size: 1.3rem;
  font-weight: 600;
  margin-bottom: 20px;
  color: #1f2937;
}

.transactions-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.transaction-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
}

.transaction-info {
  flex: 1;
}

.transaction-date {
  font-weight: 500;
  color: #1f2937;
  margin-bottom: 5px;
}

.transaction-description {
  color: #6b7280;
}

.transaction-amount {
  font-weight: 700;
  font-size: 1.1rem;
}

.transaction-amount.debit {
  color: #ef4444;
}

.transaction-amount.credit {
  color: #22c55e;
}

/* Scholarships Tab */
.scholarships-tab {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.scholarships-overview {
  display: flex;
  flex-direction: column;
  gap: 30px;
}

.scholarship-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}

.stat-card {
  display: flex;
  align-items: center;
  padding: 20px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.stat-icon {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 15px;
  font-size: 1.5rem;
  background: #dbeafe;
  color: #3b82f6;
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
}

.stat-label {
  font-size: 0.9rem;
  color: #6b7280;
}

.scholarships-list h3 {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 20px;
  color: #1f2937;
}

.scholarships-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 20px;
}

.scholarship-card {
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 20px;
  transition: all 0.2s ease;
}

.scholarship-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  border-color: #d1d5db;
}

.scholarship-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.scholarship-header h4 {
  font-size: 1.2rem;
  font-weight: 600;
  color: #1f2937;
}

.scholarship-status {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 500;
  background: #dcfce7;
  color: #166534;
}

.scholarship-details {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin-bottom: 15px;
}

/* Dark mode support */
.dark .parent-finance {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .balance-header h3,
.dark .payment-methods h3,
.invoice-header h4,
.statement-header h3,
.scholarship-header h4,
.scholarships-list h3 {
  color: #f9fafb;
}

.dark .page-header p,
.dark .detail-item span:first-child,
.detail-row span:first-child,
.transaction-description,
.stat-label {
  color: #d1d5db;
}

.dark .detail-item span:last-child,
.detail-row span:last-child,
.transaction-date,
.stat-value {
  color: #f9fafb;
}

.dark .finance-controls,
.dark .balance-card,
.dark .payment-methods,
.dark .invoices-tab,
.dark .payments-tab,
.dark .statements-tab,
.dark .scholarships-tab {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .finance-tabs {
  background: #374151;
}

.dark .finance-tabs button.active {
  background: #1f2937;
}

.dark .method-card {
  border-color: #374151;
}

.dark .method-card:hover {
  border-color: #4b5563;
  background: #111827;
}

.dark .method-card.active {
  border-color: #6366f1;
  background: #1e1b4b;
}

.dark .invoice-card {
  border-color: #374151;
}

.dark .invoice-card:hover {
  border-color: #4b5563;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.dark .payments-table {
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

.dark .statement-preview {
  border-color: #374151;
}

.dark .statement-summary {
  background: #111827;
}

.dark .summary-item {
  border-color: #374151;
}

.dark .transaction-item {
  border-color: #374151;
}

.dark .scholarship-card {
  border-color: #374151;
}

.dark .scholarship-card:hover {
  border-color: #4b5563;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.dark .no-invoices,
.dark .no-payments {
  background: #1f2937;
  color: #9ca3af;
}

.dark .no-invoices i,
.dark .no-payments i {
  color: #4b5563;
}

.dark .payment-reminder {
  background: #713f12;
  color: #fbbf24;
  border-color: #fbbf24;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .parent-finance {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .finance-controls {
    flex-direction: column;
    align-items: stretch;
  }
  
  .finance-overview {
    grid-template-columns: 1fr;
  }
  
  .table-row {
    grid-template-columns: 1fr 2fr 1fr 1fr 1fr 1fr;
  }
  
  .invoices-grid,
  .scholarships-grid {
    grid-template-columns: 1fr;
  }
  
  .invoice-details,
  .scholarship-details {
    grid-template-columns: 1fr;
  }
  
  .statement-controls {
    flex-direction: column;
    align-items: stretch;
  }
}
</style>