type SelectOptions = {
    options: Option[],
    className?: string,  
}
interface Option {
  name: string
  value: string
  required?: boolean
  selected?: boolean
}

export default function SelectOptions({options, className = ''} : SelectOptions) {
    const data = options.map((option) => {
      return (
        <option
          key={option.value}
          className={`${className} relative cursor-default select-none py-2 pl-3 pr-9`}
          value={option.value}
          selected={option.hasOwnProperty('selected') ? option.selected : false}
        >
          {option.name}
        </option>
      )
    })
  
    return (
      <>{data}</>
    )
  }