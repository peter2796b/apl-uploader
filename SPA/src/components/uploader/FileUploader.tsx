import {
	Card,
	CardContent,
	CardDescription,
	CardFooter,
	CardHeader,
	CardTitle,
} from "@/components/ui/card"
import { Button } from "../ui/button"
import { Input } from "../ui/input"
import { Label } from "../ui/label"
import { useState } from "react"
import { Alert, AlertDescription, AlertTitle } from "../ui/alert"
import { AlertCircle } from "lucide-react"


type File = {
	name: string,
	path: string,
	public_url: string,
	type: string,
	mime_type: string,
	extension: string,
	size: number
}

export default function FileUploader() {
	const UPLOAD_URL = "http://localhost:8000/api/files/upload";
	const [file, setFile] = useState(null)
	const [uploadedFile, setUploadedFile] = useState<File | null>(null)
	const [error, setError] = useState<boolean>(false)
	const [errorMessage, setErrorMessage] = useState<string>("");

	const uploadFile = async () => {
		clearError();
		const formData = new FormData();
		formData.append("file", file);

		try {
			const res = await fetch(UPLOAD_URL, {
				method: "POST",
				body: formData,
				headers: {
					"Accept": "application/json"
				}
			})
			const { data, message } = await res.json();
			if (res.status >= 400 && res.status < 600) {
				setError(true);
				setErrorMessage(message);
			} else {
				setUploadedFile(data);
				setFile(null);
			}
		} catch (err) {
			console.log(err)
		}
	}

	const clearError = () => {
		setError(false);
		setErrorMessage("");
	}

	const handleFileChange = (e) => {
		clearError();
		setUploadedFile(null);
		setFile(e.target.files[0]);
	}

	return <div className="w-1/2">
		<Card>
			<CardHeader>
				<CardTitle>File Uploader</CardTitle>
				<CardDescription>File uploader tool to upload images</CardDescription>
			</CardHeader>
			<CardContent>
				<Input type="file" accept="image/png,image/jpeg,image/jpg" onChange={handleFileChange} />
				<Label>Accepts only png/jpeg/jpg</Label>
			</CardContent>
			<CardFooter>
				{file && <Button onClick={uploadFile}>Upload</Button>}
			</CardFooter>
		</Card>

		{
			uploadedFile ? (
				<div className="mt-5">
					<Alert variant={"success"}>
						<AlertCircle className="h-4 w-4" />
						<AlertTitle>Success</AlertTitle>
						<AlertDescription>File uploaded successfully</AlertDescription>
					</Alert>
					<div className="mt-5 h-[400px] w-[400px]">
						<img src={uploadedFile.public_url} />
					</div>
				</div>
			) : null
		}

		{
			error && errorMessage.length > 0 ?
				<>
					<div className="mt-5">
						<Alert variant={"destructive"}>
							<AlertCircle className="h-4 w-4" />
							<AlertTitle>Error</AlertTitle>
							<AlertDescription>{errorMessage}</AlertDescription>
						</Alert>
					</div>
				</>
				: null
		}

	</div>
}