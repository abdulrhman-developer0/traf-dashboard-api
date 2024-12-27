<script setup>
import ar from 'apexcharts/dist/locales/ar.json'

const props = defineProps({
  chart: Array,
  title: String,
})

const chartColors = {
  line: {
    series1: '#E55175',
    series2: '#E55175',
    series3: '#E55175',
  },
}

const headingColor = 'rgba(var(--v-theme-on-background), var(--v-high-emphasis-opacity))'
const labelColor = '#140208'
const borderColor = 'rgba(var(--v-border-color), var(--v-border-opacity))'

const series = [

  {
    name: props.title,
    type: 'line',
    data: props.chart,
  },
]

const shipmentConfig = {
  chart: {
    type: 'line',
    stacked: false,
    parentHeightOffset: 0,
    toolbar: { show: false },
    zoom: { enabled: false },
    locales: [{
      "name": "ar",
      "options": {
        "months": ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
      }
    }],
    defaultLocale: "ar",
    fontFamily: "Cairo, sans-serif"

  },
  markers: {
    size: 0,
    colors: '#fff',
    strokeColors: chartColors.line.series2,
    hover: { size: 6 },
    borderRadius: 4,
  },
  stroke: {
    curve: 'smooth',
    width: [
      6
    ],
    lineCap: 'round',
  },
  legend: {
    show: true,
    position: 'bottom',
    markers: {
      width: 8,
      height: 8,
      offsetX: -3,
    },
    height: 40,
    itemMargin: {
      horizontal: 10,
      vertical: 0,
    },
    fontSize: '15px',
    fontFamily: 'Cairo',
    fontWeight: 400,
    labels: {
      colors: headingColor,
      useSeriesColors: !1,
    },
    offsetY: 10,
  },
  grid: {
    strokeDashArray: 8,
    borderColor,
  },
  colors: [
    chartColors.line.series1,
  ],
  fill: {
    opacity: [
      1
    ],
  },
  plotOptions: {
    bar: {
      columnWidth: '30%',
      borderRadius: 4,
      borderRadiusApplication: 'end',
    },
  },
  dataLabels: { enabled: false },
  xaxis: {
    tickAmount: 10,
    categories: [
      'يناير',
      'فبراير',
      'مارس',
      'ابريل',
      'مايو',
      'يونيو',
      'يوليو',
      'اغسطس',
      'سبتمبر',
      'اكتوبر',
      'نوفمبر',
      'ديسمبر',
    ],
    labels: {
      style: {
        colors: labelColor,
        fontSize: '12px',
        fontWeight: 400,
      },
    },
    axisBorder: { show: false },
    axisTicks: { show: false },
  },
  yaxis: {
    show: false,
    tickAmount: 4,
    min: 0,
    max: 50,
    labels: {
      style: {
        colors: labelColor,
        fontSize: '12px',
        fontWeight: 400,
      },
      formatter(val) {
        return `${ val }`
      },
    },
  },
  responsive: [
    {
      breakpoint: 1400,
      options: {
        chart: { height: 320 },
        xaxis: { labels: { style: { fontSize: '10px' } } },
        legend: {
          itemMargin: {
            vertical: 0,
            horizontal: 10,
          },
          fontSize: '13px',
          offsetY: 12,
        },
      },
    },
    {
      breakpoint: 1025,
      options: {
        chart: { height: 415 },
        plotOptions: { bar: { columnWidth: '50%' } },
      },
    },
    {
      breakpoint: 982,
      options: { plotOptions: { bar: { columnWidth: '30%' } } },
    },
    {
      breakpoint: 480,
      options: {
        chart: { height: 250 },
        legend: { offsetY: 7 },
      },
    },
  ],
}
</script>

<template>
  <VueApexCharts
    id="index-chart"
    type="line"
    height="269"
    :options="shipmentConfig"
    :series="series"
    />
</template>

<style lang="scss">
@use "@core-scss/template/libs/apex-chart.scss";

.v-btn-group--divided .v-btn:not(:last-child) {
  border-inline-end-color: rgba(var(--v-theme-primary), 0.5);
}

#index-chart {
  .apexcharts-legend-text {
    font-size: 16px !important;
  }

  .apexcharts-legend-series {
    border: 1px solid rgba(var(--v-theme-on-surface), 0.12);
    border-radius: 0.375rem;
    block-size: 83%;
    padding-block: 4px;
    padding-inline: 16px 12px;
  }
}
</style>
