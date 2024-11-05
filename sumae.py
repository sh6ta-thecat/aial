import tkinter as tk
from tkinter import messagebox
import requests

# URL de la API
BASE_URL = "http://localhost/api/api.php"

class App:
    def __init__(self, root):
        self.root = root
        self.root.title("Aplicación de Prueba con API")

        # Ventana de inicio de sesión
        self.login_frame = tk.Frame(root)
        self.login_frame.pack(pady=20)

        tk.Label(self.login_frame, text="Nombre").grid(row=0, column=0)
        tk.Label(self.login_frame, text="Contraseña").grid(row=1, column=0)

        self.entry_name = tk.Entry(self.login_frame)
        self.entry_password = tk.Entry(self.login_frame, show="*")
        self.entry_name.grid(row=0, column=1)
        self.entry_password.grid(row=1, column=1)

        tk.Button(self.login_frame, text="Iniciar Sesión", command=self.login).grid(row=2, columnspan=2, pady=10)

        # Ventana del perfil
        self.profile_frame = tk.Frame(root)
        tk.Label(self.profile_frame, text="Perfil del Usuario").grid(row=0, columnspan=2)
        
        self.profile_name_label = tk.Label(self.profile_frame, text="Nombre:")
        self.profile_name_label.grid(row=1, column=0)
        self.profile_name_value = tk.Label(self.profile_frame, text="")
        self.profile_name_value.grid(row=1, column=1)

        self.profile_email_label = tk.Label(self.profile_frame, text="Correo:")
        self.profile_email_label.grid(row=2, column=0)
        self.profile_email_value = tk.Label(self.profile_frame, text="")
        self.profile_email_value.grid(row=2, column=1)

        tk.Button(self.profile_frame, text="Editar Perfil", command=self.show_edit_profile).grid(row=3, columnspan=2, pady=10)

        # Ventana de edición del perfil
        self.edit_frame = tk.Frame(root)
        tk.Label(self.edit_frame, text="Editar Perfil").grid(row=0, columnspan=2)
        
        tk.Label(self.edit_frame, text="Nuevo Nombre").grid(row=1, column=0)
        self.edit_name = tk.Entry(self.edit_frame)
        self.edit_name.grid(row=1, column=1)

        tk.Label(self.edit_frame, text="Nuevo Correo").grid(row=2, column=0)
        self.edit_email = tk.Entry(self.edit_frame)
        self.edit_email.grid(row=2, column=1)

        tk.Button(self.edit_frame, text="Guardar Cambios", command=self.edit_profile).grid(row=3, columnspan=2, pady=10)
        tk.Button(self.edit_frame, text="Cancelar", command=self.show_profile).grid(row=4, columnspan=2)

    def login(self):
        # Datos para la solicitud de inicio de sesión
        name = self.entry_name.get()
        password = self.entry_password.get()

        response = requests.post(f"{BASE_URL}?action=login", data={"name": name, "password": password})
        result = response.json()

        if result["status"] == "success":
            # Guardar la información del usuario y mostrar el perfil
            self.user = result["user"]
            self.show_profile()
        else:
            messagebox.showerror("Error de inicio de sesión", result["message"])

    def show_profile(self):
        # Mostrar los datos del perfil del usuario
        self.login_frame.pack_forget()
        self.edit_frame.pack_forget()
        self.profile_frame.pack()

        self.profile_name_value.config(text=self.user["name"])
        self.profile_email_value.config(text=self.user.get("email", "No especificado"))

    def show_edit_profile(self):
        # Mostrar ventana para editar perfil
        self.profile_frame.pack_forget()
        self.edit_frame.pack()

        self.edit_name.insert(0, self.user["name"])
        self.edit_email.insert(0, self.user.get("email", ""))

    def edit_profile(self):
        # Enviar solicitud para editar el perfil del usuario
        new_name = self.edit_name.get()
        new_email = self.edit_email.get()
        response = requests.post(f"{BASE_URL}?action=edit_profile", data={"user_id": self.user["id"], "name": new_name, "email": new_email})
        result = response.json()

        if result["status"] == "success":
            messagebox.showinfo("Perfil actualizado", "Perfil actualizado correctamente")
            # Actualizar los datos en la aplicación
            self.user["name"] = new_name
            self.user["email"] = new_email
            self.show_profile()
        else:
            messagebox.showerror("Error", result["message"])

if __name__ == "__main__":
    root = tk.Tk()
    app = App(root)
    root.mainloop()
