<script setup>
import CrmActiveProject from '../views/dashboards/crm/CrmActiveProject.vue'
import CrmActivityTimeline from '../views/dashboards/crm/CrmActivityTimeline.vue'
import CrmAnalyticsSales from '../views/dashboards/crm/CrmAnalyticsSales.vue'
import CrmEarningReportsYearlyOverview from '../views/dashboards/crm/CrmEarningReportsYearlyOverview.vue'
import CrmOrderBarChart from '../views/dashboards/crm/CrmOrderBarChart.vue'
import CrmProjectStatus from '../views/dashboards/crm/CrmProjectStatus.vue'
import CrmRecentTransactions from '../views/dashboards/crm/CrmRecentTransactions.vue'
import CrmRevenueGrowth from '../views/dashboards/crm/CrmRevenueGrowth.vue'
import CrmSalesAreaCharts from '../views/dashboards/crm/CrmSalesAreaCharts.vue'
import CrmSalesByCountries from '../views/dashboards/crm/CrmSalesByCountries.vue'
import { Head, usePage } from '@inertiajs/vue3'
const page = usePage()

const user = page.props.user

const simpleStatisticsDemoCards = [
  {
    icon: 'tabler-credit-card',
    color: 'error',
    title: 'Total Profit',
    subTitle: 'Last week',
    stat: '1.28k',
    change: '-12.2%',
  },
  {
    icon: 'tabler-currency-dollar',
    color: 'success',
    title: 'Total Sales',
    subTitle: 'Last week',
    stat: '$4,673',
    change: '+25.2%',
  },
]
</script>

<template>
  <Head title="Home" /> 
  <div>
    <VCard :title="'Hello, '+ user.name">
      <VCardText>Welcome to Traf dashboard</VCardText>
    </VCard>
  </div>
</template>
