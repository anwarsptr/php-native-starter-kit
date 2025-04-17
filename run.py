import subprocess
import os
import sys
import signal

def run_php_server():
    port = os.getenv("PORT", "8080")
    print(f"Running the server on http://localhost:{port}")
    
    try:
        # Gunakan Popen supaya bisa dikontrol
        process = subprocess.Popen(["php", "-S", f"localhost:{port}", "-t", "."])
        process.wait()
    except KeyboardInterrupt:
        print("\nStopping server . . .")
        process.terminate()
        process.wait()
        print("Server stopped.")
        sys.exit(0)

if __name__ == "__main__":
    run_php_server()
