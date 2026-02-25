import { reactive } from 'vue'

export const useForm = (initial = {}) => {
  const form = reactive({ ...initial })
  return { form }
}