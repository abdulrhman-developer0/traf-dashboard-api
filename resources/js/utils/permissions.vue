<script>
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const permissions = computed(() => page.props.user.permissions)

</script>
