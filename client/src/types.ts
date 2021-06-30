export interface Department {
    id: string;
    name: string;
}

export interface DepartmentProps {
    id: string;
    name: string;
    onRemove: (id: string) => void;
    onEdit: (department: Department) => void;
}

export interface DepartmentFormState {
    show: boolean;
    department?: Department;
}

export interface DepartmentFormProps {
    formState: DepartmentFormState;
    handleClose: (load?: boolean) => void;
}

export interface DepartmentFormInput {
    name: string
}

export interface Employee {
    id: string;
    name: string;
    salary: number;
    department_id: string;
}

export interface EmployeeProps {
    employee: Employee;
    onRemove: (employee: Employee) => void;
    onEdit: (employee: Employee) => void;
}

export interface EmployeeFormState {
    show: boolean;
    employee?: Employee;
    department_id?: string;
}

export interface EmployeeFormProps {
    formState: EmployeeFormState;
    handleClose: (load?: boolean) => void;
}

export interface EmployeeFormInput {
    name: string;
    salary: number;
    department_id: string;
}

export interface ReportEntity {
    id: string;
    name: string;
    max_salary?: number;
    employee_count: number;
    rich_count?: number;
}

export interface ReportData {
    title: string;
    description: string;
    dataUrl: string;
    entities?: ReportEntity[];
}

export interface ErrorProps {
    message: string;
}
