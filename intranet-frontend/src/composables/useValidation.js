export const useValidation = () => {
  const required = (value) => !!value || 'Campo obligatorio'
  return { required }
}